//using api generated key and using URL to fetch data from website//
const apiKey="5e3c61d27c6844b92094a838749fd79a";
const apiUrl="https://api.openweathermap.org/data/2.5/weather?units=metric&q=";
////
const searchBox= document.querySelector(".search input");
const searchBtn= document.querySelector(".search button");
const weatherIcon= document.querySelector(".weather-icon");
////
// create a function to update the date and time
function updateDateTime() {
   // create a new object called date
   const now = new Date();

   // get the current date and time 
   const currentDateTime = now.toLocaleString();


   document.querySelector('#datetime').textContent = currentDateTime;
 }

 // call the updateDateTime function every second
 setInterval(updateDateTime, 1000);
async function checkWeather(city){
    const response= await fetch(apiUrl + city + `&appid=${apiKey}` );
    var data = await response.json();
    

    console.log(data);
    //using query selector function and innerHTML fucntion to set the given data//
    document.querySelector(".city").innerHTML = data.name;
    document.querySelector(".temp").innerHTML= Math.round(data.main.temp) + "°C";
    document.querySelector(".humidity").innerHTML= data.main.humidity + "%";
    document.querySelector(".wind").innerHTML= data.wind.speed + " km/h";
    document.querySelector(".time").innerHTML=data.timezone;
    document.querySelector(".windpressure").innerHTML ="wind pressure : "+ data.main.pressure + "hpa"

    //using if else to change the images according to the data given for weather//
    if(data.weather[0].main =="Clouds"){
        weatherIcon.src ="https://cdn-icons-png.flaticon.com/512/1146/1146856.png";
    }
    else if (data.weather[0].main =="Clear"){
        weatherIcon.src="https://cdn-icons-png.flaticon.com/512/3032/3032746.png";
    }
    else if(data.weather[0].main =="Rain"){
        weatherIcon.src="https://cdn-icons-png.flaticon.com/512/3767/3767039.png";
    }
    else if(data.weather[0].main == "Drizzle"){
        weatherIcon.src="https://cdn-icons-png.flaticon.com/512/1458/1458966.png";
    }
    else if(data.weather[0].main == "Mist"){
        weatherIcon.src ="https://cdn-icons-png.flaticon.com/512/1197/1197102.png";
    }
    else if(data.weather[0].main =="Haze"){
        weatherIcon.src="https://cdn-icons-png.flaticon.com/512/1779/1779807.png";
    }
    
}
//calling the function//
searchBtn.addEventListener("click", ()=>{
    checkWeather(searchBox.value);
})
checkWeather("Gandhinagar")

const options = {
   method: 'GET',
   headers: new Headers({'Content-Type': 'application/json'}),
   // mode: 'no-cors', // You might not need this
};
 
 fetch('https://animeshshrestha2408244.rf.gd/weather.php')
   .then(response => {
       // Check if the response has CORS headers
       console.log('Response Headers:', response.headers);
       return response.json();
   })
   .then(data => {
       console.log('Data:', data);
       //display_local_data(data);
       local_data(data);
       // Call the function that uses the fetched data
       displayWeatherData(data);
   })
   .catch(error => console.error('Fetch Error:', error));

   function displayWeatherData(data) {
 if (Array.isArray(data)) {
     let weekBoxHTML = "";
     let weekContainer = document.getElementById("week-container");

     if (weekContainer) { // Ensure the container exists
         data.forEach((weather) => {
             weekBoxHTML +=
                ` <div class="week-box">
                     <div class="date">${weather.Day_and_Date}</div>
                     <div class="db_info">
                         <p>${weather.Weather_Condition}</p>
                         <p>${weather.Day_of_week}</p>
                         <p>${weather.Temperature}°C</p>
                         <p>${weather.Wind_Speed} Km/Hr</p>
                         <p>${weather.Humidity}%</p>
                     </div>
                 </div>
                 <hr>`;
         });
         weekContainer.innerHTML = weekBoxHTML;
         let dbInfoElements = document.querySelectorAll(".db_info");
               dbInfoElements.forEach((dbInfoElement) => {
                   dbInfoElement.style.display = "flex";
                   dbInfoElement.style.justifyContent = "space-between";
                   dbInfoElement.style.width = "100%";
               });
     }
 }
  else {
     console.error('Invalid data format:', data);
 }
function local_data(data){
  let display = JSON.parse(localStorage.data)
  console.log("Local Storage ",display)
 }
   }