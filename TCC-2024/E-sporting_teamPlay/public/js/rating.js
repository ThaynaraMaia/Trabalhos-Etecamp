// Filters 
const menu = document.getElementById('rating');
const myStar = document.getElementById('mystar');


let value = eval(menu.value);
currentStars = value[1];
myStar.setAttribute('src', '../assets/icons/rating_'+currentStars+'.png');

menu.addEventListener('change', () => {
    let value = eval(menu.value);
    console.log('sadasd');
    console.log(value);
    currentStars = value[1];
    myStar.setAttribute('src', '../assets/icons/rating_'+currentStars+'.png');
    
    window.location.href = 'user.php?uid=' + value[0] + '&rate=' + value[1];
})

    
