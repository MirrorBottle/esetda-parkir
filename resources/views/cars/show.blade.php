<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-SETDA PARKIR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans', sans-serif;
        }

        .custom-green {
            color: #009E3B;
        }

        .bg-custom-pale-green {
            background-color: #F0FFF4;
        }

        .bg-custom-green {
            background-color: #009E3B;
        }

        .border-custom-green {
            border-color: #009E3B;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen py-8 px-3">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden m-4 border-t-4 border-custom-green">
        <div class="bg-custom-pale-green p-4 flex items-center justify-between">
            <img class="h-16 w-auto object-contain" src="{{ asset('/images/logo.png') }}"
                alt="Logo Biro Umum Kalimantan Timur" />
            <h1 class="custom-green font-bold text-xl hidden sm:block">E-SETDA PARKIR</h1>
        </div>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                @php
                    $color = [
                        'pribadi' => 'bg-red-500',
                        'operasional' => 'bg-info-500',
                        'dinas' => 'bg-green-500',
                        'lainnya' => 'bg-black-500',
                    ][$data->type];
                @endphp
                <span
                    class="{{ $color }} text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">{{ $data->type }}</span>

            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $data->plate_number }}</h2>
            <div class="bg-gray-100 p-4 rounded-lg  grid md:grid-cols-2 sm:grid-cols-1  gap-4">
                <div>
                    <span class="text-gray-800 text">Nama Pemilik:</span>
                    <p class="font-medium text-lg">{{ $data->employee->name }}</p>
                </div>
                <div class="mt-2">
                    <span class="text-gray-800 text">Jabatan Pemilik:</span>
                    <p class="font-medium text-lg">{{ $data->employee->position }}</p>
                </div>
                <div class="mt-2">
                    <span class="text-gray-800 text">No. Telepon:</span>
                    <p class="font-medium text-lg">{{ $data->employee->phone_number }}</p>
                </div>
                <div class="mt-2">
                    <span class="text-gray-800 text">Unit Kendaraan:</span>
                    <p class="font-medium text-lg">{{ $data->name }}</p>
                </div>
                <div class="mt-2">
                    <span class="text-gray-800 text">Unit Kerja:</span>
                    <p class="font-medium text-lg">{{ $data->employee->biro->name }}</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
