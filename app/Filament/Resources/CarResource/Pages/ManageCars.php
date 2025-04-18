<?php

namespace App\Filament\Resources\CarResource\Pages;

use App\Filament\Resources\CarResource;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ManageRecords;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ManageCars extends ManageRecords
{
    protected static string $resource = CarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon("heroicon-o-plus")
                ->label("Kendaraan"),
            Actions\Action::make('export')
                ->label('Laporan')
                ->icon('heroicon-o-arrow-down-tray')
                ->modalHeading('Laporan Kendaraan')
                ->modalSubmitActionLabel('Ekspor Laporan')
                ->form([
                    Select::make('type')
                        ->label('Tipe')
                        ->options([
                            'all' => 'Semua',
                            'dinas' => 'Dinas',
                            'operasional' => 'Operasional',
                            'pribadi' => 'Pribadi',
                            'lainnya' => 'Lainnya',
                        ])
                        ->searchable()
                        ->default('all'),
                    Select::make('biro_id')
                        ->label('Biro')
                        ->searchable()
                        ->relationship('employee.biro', 'name')
                        ->hint('*Kosongkan apabila memilih semua')
                        ->preload(),
                ])
                ->action(function (array $data) {
                    // Load template
                    $templatePath = public_path('templates/car_template.xlsx');
                    $spreadsheet = IOFactory::load($templatePath);
                    $sheet = $spreadsheet->getActiveSheet();

                    // Build query with filters
                    $query = $this->getResource()::getModel()::query();

                    if (isset($data['biro_id'])) {
                        $query->whereHas('employee', function($query) use ($data) {
                            $query->where('biro_id', $data['biro_id']);
                        });
                    }

                    if (isset($data['type']) && $data['type'] !== 'all') {
                        $query->where('type', $data['type']);
                    }

                    $records = $query->join('employees', 'cars.employee_id', '=', 'employees.id')
                        ->join('biros', 'employees.biro_id', '=', 'biros.id')
                        ->orderBy('biros.id') // or 'biros.id', depending on your need
                        ->select('cars.*') // important to select only car columns for Eloquent Car collection
                        ->with('employee.biro') // eager load relationships if needed
                        ->get();

                    // Fill data
                    $row = 3;
                    foreach ($records as $key => $record) {
                        $sheet->setCellValue('A' . $row, $key + 1);
                        $sheet->setCellValue('B' . $row, $record->employee->biro->name);
                        $sheet->setCellValue('C' . $row, $record->employee->phone_number);
                        $sheet->setCellValue('D' . $row, $record->employee->position);
                        $sheet->setCellValue('E' . $row, $record->plate_number);
                        $sheet->setCellValue('F' . $row, $record->name);
                        $sheet->setCellValue('G' . $row, $record->type);
                        $sheet->getStyle("A$row:G$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $row++;
                    }

                    // Generate download
                    return response()->streamDownload(function () use ($spreadsheet) {
                        $writer = new Xlsx($spreadsheet);
                        $writer->save('php://output');
                    }, "sisda-parkir-". now()->format('Y-m-d') .".xlsx"); 
                }),
        ];
    }
}
