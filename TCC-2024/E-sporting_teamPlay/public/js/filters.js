// Filters 
const filters = document.getElementsByClassName('sideicon');
let lastFilter = 1;
let currentFilter = 1;


console.log(filters[8]);
filters[lastFilter].setAttribute('class', `sideicon j${lastFilter} filtered`)


for (let i = 1; i < filters.length; i++) {

    filters[i].addEventListener('click', (e) => {
        lastFilter = currentFilter;
        // console.log(`Before:\nC: ${currentFilter}\nL: ${lastFilter}`);
        console.log(e)

        // e.target.setAttribute('class', `sideicon j${i-1} filtered`) 
        // filters[i].setAttribute('class', `sideicon j${i-1} filtered`) 
        filters[i].setAttribute('class', `sideicon j${i} filtered`) 
        
        currentFilter = i;
        
        filters[lastFilter].setAttribute('class', `sideicon j${lastFilter}`) 
        if (currentFilter == lastFilter) {
            currentFilter = 1;
            filters[1].setAttribute('class', `sideicon j1 filtered`) 
        } 
        // console.log(`Now:\nC: ${currentFilter}\nL: ${lastFilter}`);

    })
    
}

