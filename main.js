let IsLogoMoon = false;
let isNight = false;
let rain = false;
let isBad = false;
let israin = false;
let issun = false;

var date = new Date();
var h = date.getHours(); // 0 - 23
var m = date.getMinutes(); // 0 - 59
var s = date.getSeconds(); // 0 - 59
var session = "AM";

setInterval('autoRefresh()', 10000);//reload automatique
setInterval('checkingtempwet()', 1);
setInterval('time_of_day()',1000);
showTime();

function autoRefresh() {
    window.location = window.location.href;
}

function nightmode() {
    isNight =! isNight;

    const obj = document.getElementById("shiningobj");

    document.body.style.background = isNight
        ? "linear-gradient(to right, #2c3e50,rgb(76, 161, 175))" // Night colors
        : "linear-gradient(to right,rgb(227, 246, 255),rgb(139, 205, 255))"; // Day colors

        document.querySelector("h1").style.background = isNight
        ? " #2c3e50"
        : "#3398ff";
        if (isNight == true){
            obj.classList.remove("sun");
            obj.classList.add("moon");
        }
        if (isNight == false) {
            obj.classList.remove("moon");
            obj.classList.add("sun");
        }
}

function badWeather(){
    isBad =! isBad;
    const bad = document.getElementById('badweather');
    bad.style.background = isBad
    ?"linear-gradient(to right,rgb(122, 132, 143),rgb(195, 195, 195))"
    : "linear-gradient(to right,rgb(227, 246, 255),rgb(139, 205, 255)"; // Day colors

    document.querySelector("h1").style.background = isBad
    ? "rgb(97, 102, 106)"
    : "#3398ff";
}

function logomode() {
    IsLogoMoon = !IsLogoMoon;
    const imgElement = document.querySelector('img');
    if (imgElement) {
        imgElement.src = IsLogoMoon ? '/moon.png' : '/sun.png';
    }
}

function getRandomInt(max) {
    return Math.floor(Math.random() * max);
}

function RAIN(){
    const warmth = document.getElementById("warmth");
    const WET = document.getElementById("wet");
    let t=getRandomInt(21);
    let h=getRandomInt(100);
    
    warmth.innerHTML=`🌡️${t} °C`
    wet.innerHTML=`💧${h}%`
}

function sun(){
    badWeather()
}

function checkingtempwet(){
    const rain = document.getElementById("raining");
    const warmth = document.getElementById("warmth");
    const WET = document.getElementById("wet");
    
    if (warmth.innerHTML <='🌡️20°C'){
        if (WET.innerHTML >='💧50%'){
            rain.classList.add("riskofrain");
        }
    }
}

function showTime(){
    if(h == 0){
        h = 12;
    }
    if(h > 12){
        h = h - 12;
        session = "PM";
    }
    h = (h < 10) ? "0" + h : h;
    m = (m < 10) ? "0" + m : m;
    s = (s < 10) ? "0" + s : s;
    var time = h + ":" + m + ":" + s + " " + session;
    document.getElementById("MyClockDisplay").innerText = time;
    document.getElementById("MyClockDisplay").textContent = time;
    setTimeout(showTime, 1000);
}
function time_of_day(){
    if(h>=19&&h<=8){
        isNight=true
        document.body.style.background ="linear-gradient(to right, #2c3e50,rgb(76, 161, 175))"
        
    }else{
        isNight=false
        document.body.style.background ="linear-gradient(to right,rgb(227, 246, 255),rgb(139, 205, 255)"
    }
}

