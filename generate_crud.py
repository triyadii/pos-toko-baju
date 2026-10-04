import os
import subprocess

def run(cmd):
    subprocess.run(cmd, shell=True, check=True, cwd="/var/www/html/pos-toko-baju")

models = ["JenisBarang", "StatusBarang", "Warna", "Ukuran", "Barang", "BarangVarian"]

for model in models:
    run(f"php artisan make:model {model} -m")
    run(f"php artisan make:controller {model}Controller")
