// Filters 
const statusIcon = document.getElementById('status');
const buttonPfp = document.getElementsByClassName('pfpimg');
const statusDiv = document.getElementById('statusdiv');
let currentStatus = 1;
setStatus(currentStatus);



// console.log(filters[8]);
// filters[lastFilter].setAttribute('class', `sideicon j${lastFilter} filtered`)

buttonPfp[0].addEventListener('click', (e) => {
    console.log(e);
    currentStatus = currentStatus >=2 ? 0 : currentStatus + 1;
    setStatus(currentStatus);
    // statusDiv.style.visibility = 'visible';
})

// for (let i = 1; i < filters.length; i++) {

//     filters[i].addEventListener('click', (e) => {
//         lastFilter = currentFilter;
//         // console.log(`Before:\nC: ${currentFilter}\nL: ${lastFilter}`);
//         console.log(e)

//         // e.target.setAttribute('class', `sideicon j${i-1} filtered`) 
//         filters[i].setAttribute('class', `sideicon j${i-1} filtered`) 
        
//         currentFilter = i;
        
//         filters[lastFilter].setAttribute('class', `sideicon j${lastFilter-1}`) 
//         if (currentFilter == lastFilter) {
//             currentFilter = 1;
//             filters[1].setAttribute('class', `sideicon j1 filtered`) 
//         } 
//         console.log(`Now:\nC: ${currentFilter}\nL: ${lastFilter}`);

//     })
    
// }


function setStatus(s) {
    switch (s) {
        // On
        case 1: statusIcon.setAttribute('class', 'statusind online'); break;
        // Play
        case 2: statusIcon.setAttribute('class', 'statusind playing'); break;
        // Off
        case 0: statusIcon.setAttribute('class', 'statusind offline'); break;
    }
}