
let currentTime = new Date();
let month = currentTime.getMonth() + 1;
let element = document.getElementById('header-wrapper');
console.log(month);
console.log(element);
switch (month) {
    case 1:
        element.setAttribute('style','background-image: url("api/assets/images/january-min.png")');
        element.style.backgroundRepeat = "no-repeat";
      break;
    case 2:
        element.setAttribute('style','background-image: url("api/assets/images/february-min.png")');
        element.style.backgroundRepeat = "no-repeat";
      break;
    case 3:
        element.setAttribute('style','background-image: url("api/assets/images/march-min.png")');
        element.style.backgroundRepeat = "no-repeat";
      break;
    case 4:
        element.setAttribute('style','background-image: url("api/assets/images/april-min.png")');
        element.style.backgroundRepeat = "no-repeat";
      break;
    case 5:
        element.setAttribute('style','background-image: url("api/assets/images/may-min.png")');
        element.style.backgroundRepeat = "no-repeat";
      break;
    case 6:
        element.setAttribute('style','background-image: url("api/assets/images/june-min.png")');
        element.style.backgroundRepeat = "no-repeat";
      break;
    case 7:
        element.setAttribute('style','background-image: url("api/assets/images/july-min.png")');
        element.style.backgroundRepeat = "no-repeat";
    break;
    case 8:
        element.setAttribute('style','background-image: url("api/assets/images/august-min.png")');
        element.style.backgroundRepeat = "no-repeat";
        break;
    case 9:
        element.setAttribute('style','background-image: url("api/assets/images/septembermin.png")');
        element.style.backgroundRepeat = "no-repeat";
        break;
    case 10:
        element.setAttribute('style','background-image: url("api/assets/images/october-min.png")');
        element.style.backgroundRepeat = "no-repeat";
        break;
    case 11:
        element.setAttribute('style','background-image: url("api/assets/images/november-min.png")');
        element.style.backgroundRepeat = "no-repeat";
        break;
    case 12:
        element.setAttribute('style','background-image: url("api/assets/images/december-min.png")');
        element.style.backgroundRepeat = "no-repeat";
        break;
   
  }
//element.style.backgroundRepeat = "no-repeat"; // The background image will not repeat (default is repeat)
//element.style.backgroundPosition = "center";  // The background image will be centered
