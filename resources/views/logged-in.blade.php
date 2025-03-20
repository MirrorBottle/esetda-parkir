<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>E-SETDA PARKIR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/lucide-static@0.344.0/font/lucide.css">
  </head>
  <body>
    <div class="min-h-screen bg-emerald-50 flex flex-col items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full text-center space-y-6">
        <div class="flex justify-center">
          <img 
            src="{{ asset('images/logo.png') }}" 
            alt="Success"
            class="h-15"
          />
        </div>
        
        <div class="space-y-4">
          <div class="flex items-center justify-center text-emerald-600">
            <i class="lucide-check-circle w-8 h-8"></i>
          </div>
          
          <h1 class="text-2xl font-bold text-gray-800">
            E-SETDA PARKIR
          </h1>
          
          <p class="text-gray-600">
            Anda berhasil login menggunakan sebagai <br> <b>Pengawas Dalam</b>.
          </p>
        </div>
      </div>
    </div>
  </body>
</html>