<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Filament\Resources\CarResource\RelationManagers;
use App\Models\Car;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Laravel\Facades\Image;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = -2;

    protected static ?string $label = 'Kendaraan';
    public static ?string $pluralModelLabel = 'Kendaraan';

    /**
     * Get the navigation badge for the resource.
     */
    public static function getNavigationBadge(): ?string
    {
        return number_format(static::getModel()::count());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->label('Pegawai')
                    ->searchable()
                    ->relationship('employee', 'name')
                    ->required()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label("Nama Kendaraan")
                    ->maxLength(255),
                Forms\Components\TextInput::make('plate_number')
                    ->label("Plat Nomor")
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'dinas' => 'Dinas',
                        'operasional' => 'Operasional',
                        'pribadi' => 'Pribadi',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('note')
                    ->label("Catatan")
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.biro.name')
                    ->badge()
                    ->label('Biro')
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Pegawai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label("Nama Kendaraan")
                    ->searchable(),
                Tables\Columns\TextColumn::make('plate_number')
                    ->label("Plat Nomor")
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors(['primary' => 'dinas', 'info' => 'operasional', 'danger' => 'pribadi', 'warning' => 'lainnya'])
                    ->sortable()
                    ->label('Tipe'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'dinas' => 'Dinas',
                        'operasional' => 'Operasional',
                        'pribadi' => 'Pribadi',
                        'lainnya' => 'Lainnya',
                    ]),
                Tables\Filters\SelectFilter::make('employee.biro_id')
                    ->label('Biro')
                    ->relationship('employee.biro', 'name')
                    ->columnSpanFull()
                    ->preload()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\Action::make('qrImage')
                    ->label('QR Code')
                    ->icon('heroicon-o-qr-code')
                    ->color('info')
                    ->action(function (Car $record) {
                        // Check if QR image already exists
                        // if ($record->qr_image !== null) {
                            // If exists, just download it
                            // return response()->download(Storage::disk('public')->path($record->qr_image));
                        // }

                        // Generate QR code with the link
                        $qrData = env("APP_URL") . "/detail-kendaraan/{$record->uuid}";

                        // Generate QR code
                        $qrcode = QrCode::format('png')
                            ->size(850)
                            ->errorCorrection('H')
                            ->generate($qrData);

                        // Save QR code temporarily
                        $tempDir = Storage::disk('public')->path('temp');
                        if (!file_exists($tempDir)) {
                            mkdir($tempDir, 0755, true);
                        }
                        $qrPath = $tempDir . '/temp_qr.png';
                        file_put_contents($qrPath, $qrcode);

                        // Initialize Image Manager with driver
                        $manager = new \Intervention\Image\ImageManager(
                            new \Intervention\Image\Drivers\Gd\Driver()
                            // Or use Imagick: new \Intervention\Image\Drivers\Imagick\Driver()
                        );

                        // Load the template image
                        $template_path = in_array($record->type, ["dinas", "operasional"]) ?
                            public_path('images/templates_dinas.png') :
                            public_path('images/templates.png');
                        $template =  $manager->read($template_path);

                        // Load the QR code
                        $qrImage = $manager->read($qrPath);

                        // Get image dimensions
                        $templateWidth = $template->width();
                        $templateHeight = $template->height();
                        $qrWidth = $qrImage->width();
                        $qrHeight = $qrImage->height();

                        // Calculate center position
                        $centerX = ($templateWidth / 2) - ($qrWidth / 2);
                        $centerY = ($templateHeight / 2) - ($qrHeight / 2) + 40;

                        // Insert QR code onto template
                        $template->place($qrImage, 'top-left', (int)$centerX, (int)$centerY);

                        // Save the final image
                        $outputPath = "parking_passes/{$record->uuid}.png";
                        $outputFullPath = Storage::disk('public')->path($outputPath);

                        // Make sure directory exists
                        $outputDir = dirname($outputFullPath);
                        if (!file_exists($outputDir)) {
                            mkdir($outputDir, 0755, true);
                        }

                        // Save image
                        $template->toPng()->save($outputFullPath);

                        // Clean up temporary file
                        if (file_exists($qrPath)) {
                            unlink($qrPath);
                        }

                        // Update the record with the QR image path
                        $record->update([
                            'qr_image' => $outputPath
                        ]);
                        return response()->download($outputFullPath);
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCars::route('/'),
        ];
    }
}
