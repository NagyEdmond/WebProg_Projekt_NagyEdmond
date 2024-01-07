function sendCurNameAndAmount(cur1, cur2, amount, convResult){

    let url = "../../BACKEND/exchange_calc.php?cur1=" + cur1 + "&cur2=" + cur2 + "&amount=" + amount
    fetch(url)
    .then(response => response.text())
    .then(data => {
        convResult.value = data;
    })

}

function onChange(){
    let amountInput = document.getElementById("convValue");
    let val = parseFloat(amountInput.value);

    let convResult = document.getElementById("convResult");
    if(!isNaN(val)){
        if(val > 0){
            let cur1 = document.getElementById("cur1").value;
            let cur2 = document.getElementById("cur2").value;

            sendCurNameAndAmount(cur1, cur2, val, convResult);
        }else{
            convResult.value = "";
        }
        
    }else{
        convResult.value = "";
    }
}

let input = document.getElementById("convValue");
let cur1 = document.getElementById("cur1");
let cur2 = document.getElementById("cur2");

input.addEventListener("input", onChange);
cur1.addEventListener("change", onChange);
cur2.addEventListener("change", onChange);