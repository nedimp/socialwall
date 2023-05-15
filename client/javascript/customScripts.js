function equalHeight(selector){
    if(selector){
        let maxHeight = 0;
        let items = document.querySelectorAll(selector);
        items.forEach((item) => {
            if(item.clientHeight > maxHeight){
                maxHeight = item.clientHeight;
            }
        })
        if(maxHeight > 0){
            items.forEach((item) => {
                item.style.height = maxHeight + 'px';
            })
        }
    }
}