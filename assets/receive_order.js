const receiveOrder = async orderId => {
    const myInit = {
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            'orderId': orderId
        })
    };

    const myRequest = new Request('./receive-order', myInit);

    const response = await  fetch(myRequest, myInit);

    if (response.ok) {
        document.querySelector(".receive-order[data-order_id='"+orderId+"']").parentElement.previousElementSibling.innerHTML = 'Reçue';
        document.querySelector(".receive-order[data-order_id='"+orderId+"']").remove();
    } else {
        console.log('something went wrong');
    }
};


window.addEventListener('DOMContentLoaded',  () => {

    const receiveBtns = document.querySelectorAll('.receive-order');

    if (receiveBtns.length > 0) {
        for (receiveBtn of receiveBtns) {
            receiveBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                const orderId = this.dataset.order_id;
                await receiveOrder(orderId);
            });
        }
    }
});