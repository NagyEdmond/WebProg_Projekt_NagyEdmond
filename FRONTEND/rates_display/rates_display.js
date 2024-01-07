var chart = null;

fetch("../../BACKEND/MultipleCurrencyHelper.php")
  .then(response => response.json())
  .then(data => {
    curNames = ["EUR", "USD"];
    curDate = data[0];
    curValues = data[1];

    const datasets = []; // Array to store datasets

    for(i = 0; i < curNames.length; i++){
      const dataset = {
        label: curNames[i], // Label for the dataset
        data: curValues[i], // Assuming valuesList is an array of arrays
        borderColor: `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 1)`, // Random border color
      };

      datasets.push(dataset); // Add the dataset to the datasets array
    }

    // Create the chart with the generated datasets
    chart =  new Chart("rates", {
          type: "line",
          data: {
            labels: curDate,
            datasets: datasets // Assign the generated datasets array
          },
          options : {
            scales: {
              yAxes: [{
                scaleLabel: {
                  display: true,
                  labelString: 'Price relative to Euros'
                }
              }]
            }     
          }
        });
  });

function getSelectedCheckboxes() {
  var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');
  var selectedValues = [];
  checkboxes.forEach(function(checkbox) {
      selectedValues.push(checkbox.id);
  });

  return selectedValues;
}

function getRelevantCurrencies(chart){
  let selectedValues = getSelectedCheckboxes();
  selectedValues.push("EUR");
  selectedValues.push("USD");

  encodedValues = JSON.stringify(selectedValues);

  fetch("../../BACKEND/MultipleCurrencyHelper.php?selectedValues=" + encodedValues)
  .then(response => response.json())
  .then(data => {
    let valuesList = data[1];

    updateGraph(chart, valuesList, selectedValues);
  })

}

let checkboxes = document.querySelectorAll('input[type=checkbox]');

checkboxes.forEach(function(checkbox) {
  checkbox.addEventListener('change', function() {
    getRelevantCurrencies(chart);
  });
})

function updateGraph(chart, valuesList, labelsList) {

  chart.data.datasets = [];

  for(i = 0; i < valuesList.length; i++){


      var dataset = {
        label: labelsList[i],
        data: valuesList[i],
        borderColor: `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 1)`,
      }

      chart.data.datasets.push(dataset);

    }

    chart.update();
    console.log(chart.data.datasets)
  }