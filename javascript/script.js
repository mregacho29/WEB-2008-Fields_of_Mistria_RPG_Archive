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











/********************************************
    function:    sortCharacters
    Description: sort characters based on the selected criteria
*********************************************/
function sortCharacters(order) {
    debugMessage("function sortCharacters");

    let container = document.getElementById("character-container");
    let characters = Array.from(container.getElementsByClassName("character-box"));

    characters.sort((a, b) => {
        let nameA = a.querySelector(".character-name").innerText.toUpperCase();
        let nameB = b.querySelector(".character-name").innerText.toUpperCase();
        let dateA = new Date(a.dataset.created);
        let dateB = new Date(b.dataset.created);

        if (order === "A-Z") {
            return nameA.localeCompare(nameB);
        } else if (order === "Z-A") {
            return nameB.localeCompare(nameA);
        } else if (order === "Newest") {
            return dateB - dateA;
        } else if (order === "Oldest") {
            return dateA - dateB;
        }
    });

    container.innerHTML = "";
    characters.forEach(character => container.appendChild(character));
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