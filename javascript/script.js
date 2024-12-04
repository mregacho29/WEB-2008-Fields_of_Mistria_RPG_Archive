/******w**************
    
    Assignment 4 Javascript
    Name: Ma Crizza Lynne Regacho
    Date: 2024-12-04
    Description: Final Project

*********************/

/******************************
    function:     debugMessage
    description:  used for testing
************************************/
function debugMessage(data) {
    console.log(data);
}

/********************************************
    function:    clearTable
    Description: clear the table before displaying new results
*********************************************/
function clearTable() {
    let table = document.getElementById("output");
    while (table.rows.length > 0) {
        table.deleteRow(0);
    }
}

/********************************************
    function:    displayResults
    Description: display results of query to bottom of screen
*********************************************/
function displayResults() {
    debugMessage("function displayResults");

    clearTable(); 

    let search = document.getElementById("search").value.trim();
    let url = `view_character.php?search=${encodeURIComponent(search)}`;
    debugMessage("URL for fetch");
    debugMessage(url);

    fetch(url)
        .then(function (response) {
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.statusText}`);
            }
            return response.text();
        })
        .then(function (html) {
            debugMessage("Fetched data:");
            debugMessage(html);

            document.getElementById("output").innerHTML = html;
        })
        .catch(function (error) {
            console.error("Error fetching data:", error);
            document.getElementById("numberOfResults").innerHTML = `Error fetching data: ${error.message}`;
        });
}

/******************************
    function:     load
    description:  execute load function
************************************/
document.addEventListener("DOMContentLoaded", load);

// listen for click event, execute search function
function load() {
    // Listen for form submit button click
    document.getElementById("button").addEventListener("click", function(event) {
        event.preventDefault();
        displayResults();
    });

    console.log("load executed");
}







// how to fix the sorting type

/********************************************
    function:    fetchCharacters
    Description: fetch all characters from the server
*********************************************/
function fetchCharacters(order) {
    debugMessage("function fetchCharacters");

    let search = document.getElementById("search").value.trim();
    let url = `view_character.php?search=${encodeURIComponent(search)}&order=${order}`;
    debugMessage("URL for fetch");
    debugMessage(url);

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            debugMessage("Fetched data:");
            debugMessage(html);

            document.getElementById("character-container").innerHTML = html;
        })
        .catch(error => {
            console.error("Error fetching data:", error);
        });
}

/********************************************
    function:    sortCharacters
    Description: sort characters based on the selected criteria
*********************************************/
function sortCharacters(order) {
    debugMessage("function sortCharacters");

    fetchCharacters(order);
}

/******************************
    function:     load
    description:  execute load function
************************************/
document.addEventListener("DOMContentLoaded", load);

function load() {
    // Attach event listeners to the sort dropdown items
    document.querySelectorAll(".sort-option").forEach(item => {
        item.addEventListener("click", function(event) {
            event.preventDefault();
            let order = event.target.innerText;
            document.getElementById("dropdownMenuButton").innerText = order;
            sortCharacters(order);
        });
    });

    console.log("load executed");
}