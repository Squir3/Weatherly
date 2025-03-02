# Weatherly

Weatherly is a web application that allows users to view current weather data for a selected location. The application is built using technologies such as Vue.js, Slim Framework, Doctrine ORM, MySQL, Nginx and Redis.

## Features

- **Current Weather Data**: View current temperature, humidity, and weather description for a selected location.
- **Geolocation**: Automatically detect the user's location and display the weather data for that location.
- **Map Interaction**: Click on the map to select a different location and view its weather data.
- **Caching**: Use Redis to cache weather data and reduce the number of API calls to the weather service.
- **Responsive Design**: The application is designed to work on both desktop and mobile devices.
- **Theme Switching**: Users can switch between light and dark themes for a better user experience.

## Screenshots
![chrome_7BM8NZsTeN](https://github.com/user-attachments/assets/24c00a81-f4ed-4fcb-a243-2f2b3b36c91f)
![chrome_s1hoMBq4YD](https://github.com/user-attachments/assets/8b1efabf-f26a-4fa9-824b-96461b6e37f4)


## Installation locally

1. Clone the repository:

```sh
git clone https://github.com/Squir3/Weatherly.git
```

2. Go to the project directory

```sh
cd weatherly
```

3. Set up the backend:

```sh
cd backend
composer install
```

4. Set up the frontend:

```sh
cd frontend
npm install
```

5. Start the application using Docker Compose:

```sh
docker-compose up -d --build
```

The application will be available at http://localhost
