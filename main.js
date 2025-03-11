let IsLogoMoon = false;
let isNight = false;
let rain = false;
let israin = false;
let issun = false;

function autoRefresh() {
    window.location = window.location.href;
}

function nightmode() {
    isNight =! isNight;

    document.body.style.background = isNight
        ? "linear-gradient(to right, #2c3e50,rgb(76, 161, 175))"  // Night colors
        : "linear-gradient(to right,rgb(227, 246, 255),rgb(139, 205, 255)"; // Day colors

        document.querySelector("h1").style.background = isNight
        ? " #2c3e50"
        : "#3398ff";
}

function badWeather(){
    isBad =! isBad;
    
    document.body.style.background = isBad
    ? "linear-gradient(to right, #2c3e50,rgb(75, 87, 89))"
    : "linear-gradient(to right,rgb(122, 132, 143),rgb(195, 195, 195))";

    document.querySelector("h1").style.background = isBad
    ? "rgb(97, 102, 106)"
    : "#65717d";
}

function logomode() {
    IsLogoMoon = !IsLogoMoon;
    const imgElement = document.querySelector('img');
    if (imgElement) {
        imgElement.src = IsLogoMoon ? '/moon.png' : '/sun.png';
    }
    
}

function RAIN(){
    if (israin == true){
        israin = false;
    }else{
        israin = true;
    }
    const rain = document.getElementById("raining");
    
    if (israin == true){
        rain.classList.remove("riskofrain");
    }
    if (israin == false) {
        rain.classList.add("riskofrain");
    }
}

function sun(){
    if (issun == true){
        issun = false;
    }else{
        issun = true;
    }
    const obj = document.getElementById("shiningobj");
    var shining=obj.class
    if (issun == true){
        obj.classList.remove("sun");
    }
    if (issun == false) {
        obj.classList.add("sun");
    }
}

setInterval('autoRefresh()', 10000);//reload automatique