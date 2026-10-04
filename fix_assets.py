import os

directories = ['/var/www/html/pos-toko-baju/resources/views']

for root_dir, dirs, files in os.walk(directories[0]):
    for file in files:
        if file.endswith('.blade.php'):
            filepath = os.path.join(root_dir, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Replace relative assets with absolute ones from root
            content = content.replace('href="assets/', 'href="/assets/')
            content = content.replace('src="assets/', 'src="/assets/')
            content = content.replace("url('assets/", "url('/assets/")
            content = content.replace('url("assets/', 'url("/assets/')
            
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)

print("Assets fixed!")
