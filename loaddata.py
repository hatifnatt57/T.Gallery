import requests

url = 'http://localhost/projects/tom/admin/add/'
data = {
    'author': 'Трактуев',
    'title': 'Баклажаны',
    'size': '',
    'technique': '',
    'year': '2015',
    'genre': '',
    'description': '',
    'category': 'Графика'
}

num_requests = 100

for i in range(num_requests):
    file = open('osen.jpg', 'rb')
    files = {'image': file}
    response = requests.post(url, data=data, files=files)
    file.close()
    print(f'Response {i+1}: {response.status_code}')