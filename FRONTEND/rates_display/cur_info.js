function displayCurInfo(){

    let curValue = document.getElementById("rateValue");
    let loader = document.getElementById("loaderIcon");
    curValue.style.display = "none";
    loader.style.display = "inline-block";

    
    let curName = document.getElementById("currencySelector").value;
    fetch("../../BACKEND/SingleCurrencyHelper.php?curName=" + curName)
    .then(response => response.text())
    .then(data => {

        curValue.textContent = data;
        curValue.style.display = "inline";
        loader.style.display = "none";
    })
}