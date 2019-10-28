document.addEventListener("DOMContentLoaded", function() {
    // code...
    // Log the clicked element in the console
    let stepKeys = ['select','sno','target', 'title', 'content', 'placement'];

    //add
    document.addEventListener('click', function (event) {
        // If the clicked element doesn't have the right selector, bail
        if (!event.target.matches('#add-new-tour')) return;

        // Don't follow the link
        event.preventDefault();

        // Find a <table> element with id="myTable":
        let tourTable = document.getElementById("tour-details");
        let tableLength = tourTable.getElementsByTagName("tr").length;
        let tableColumn = tourTable.rows[0].cells.length;
        // Create an empty <tr> element and add it to the 1st position of the table:
        let row = tourTable.insertRow(tableLength);

        for(let i = 0; i < tableColumn; i++){
            // Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
            let cell1 = row.insertCell(i);

            if(stepKeys[i] === 'placement'){
                cell1.innerHTML =  '<label><select class="form__field">'+
                                        '<option value="right">Right</option>'+
                                        '<option value="left">Left</option>'+
                                        '<option value="bottom">Bottom</option>'+
                                        '<option value="top">Top</option>'+
                                    '</select></label>';
            }
            else{
                if( i === 0){
                    // Add some text to the new cells:
                    cell1.innerHTML = '<label><input type="checkbox" name="table-selection" id="row-'+tableLength+'"></label>';
                }
                else if(i === 1){
                    cell1.innerHTML = tableLength.toString();
                }
                else {
                    // Add some text to the new cells:
                    cell1.innerHTML = "New"+ (tableLength);
                    cell1.setAttribute('contentEditable', 'true');
                }

            }

        }

    }, false);

    //remove
    document.addEventListener('click', function (event) {
        // If the clicked element doesn't have the right selector, bail
        if (!event.target.matches('#remove-selected-tour')) return;

        // Don't follow the link
        event.preventDefault();

        let tourTable = document.getElementById("tour-details");
        let tableLength = tourTable.getElementsByTagName("tr").length;

        for(let i = 0; i < tableLength; i++){
            if(i !== 0){
                if(tourTable.rows[i] !== undefined){
                    let firstColumn = tourTable.rows[i].cells[0];
                    let isChecked = firstColumn.children[0].children[0].checked;
                    if(isChecked){
                        tourTable.deleteRow(i);
                        i--;
                    }
                }
            }
        }

        tableIndexing();

    }, false);

    //save
    document.addEventListener('click', function (event) {
        // If the clicked element doesn't have the right selector, bail
        if (!event.target.matches('#save-tour')) return;

        let tourTable = document.getElementById("tour-details");
        let tableLength = tourTable.getElementsByTagName("tr").length;
        let tableColumn = tourTable.rows[0].cells.length;

        // Don't follow the link
        event.preventDefault();
        let tour = {
            id: null,
            steps: []
        };

        let tourName = document.getElementById('tour-name').value;
        if(tourName === ''){
            alert('Please enter the Tour Name');
            return;
        }
        tourName = tourName.replace(/\s/g,'-');
        tour['id'] = tourName.toLowerCase();

        for(let i = 0; i < tableLength; i++){
            if(i !== 0){
                let newSteps = {};
                for (let c = 2; c < tableColumn; c++){
                    if(stepKeys[c] === 'placement'){
                        newSteps[stepKeys[c]] = tourTable.rows[i].cells[c].children[0].children[0].value;
                    }
                    else{
                        newSteps[stepKeys[c]] = tourTable.rows[i].cells[c].innerText;
                    }
                }
                tour.steps.push(newSteps);
            }
        }

        var e = document.getElementById("routes");
        var value = e.options[e.selectedIndex].value;
        var text = e.options[e.selectedIndex].text;

        sendTourData(tour, text);


    }, false);



    //save
    document.addEventListener('click', function (event) {
        // If the clicked element doesn't have the right selector, bail
        if (!event.target.matches('#update-tour')) return;

        let tourTable = document.getElementById("tour-details");
        let dataUpdate = document.getElementById('update-tour').dataset['tourId'];

        let tableLength = tourTable.getElementsByTagName("tr").length;
        let tableColumn = tourTable.rows[0].cells.length;

        // Don't follow the link
        event.preventDefault();
        let tour = {
            id: null,
            steps: []
        };

        let tourName = document.getElementById('tour-name').value;
        if(tourName === ''){
            alert('Please enter the Tour Name');
            return;
        }
        tourName = tourName.replace(/\s/g,'-');
        tour['id'] = tourName.toLowerCase();

        for(let i = 0; i < tableLength; i++){
            if(i !== 0){
                let newSteps = {};
                for (let c = 2; c < tableColumn; c++){
                    if(stepKeys[c] === 'placement'){
                        newSteps[stepKeys[c]] = tourTable.rows[i].cells[c].children[0].children[0].value;
                    }
                    else{
                        newSteps[stepKeys[c]] = tourTable.rows[i].cells[c].innerText;
                    }
                }
                tour.steps.push(newSteps);
            }
        }

        var e = document.getElementById("routes");
        var value = e.options[e.selectedIndex].value;
        var text = e.options[e.selectedIndex].text;

        sendTourData(tour, text, dataUpdate);


    }, false);


    //ajax
    function sendTourData(_tour, _route, _tourID) {
        console.log(_tour);
        let http = new XMLHttpRequest();
        if(_tourID){
            http.open('POST', '/productTour/update/'+_tourID, true);
        }else{
            http.open('POST', '/productTour/save', true);
        }


        http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        http.setRequestHeader('X-CSRF-TOKEN', document.getElementsByName('csrf-token')[0].getAttribute('content'));

        http.onreadystatechange = function() {
            //Call a function when the state changes.
            if(http.readyState === 4 && http.status === 200) {
                let result = JSON.parse(http.response);
                if(result.status){
                    window.location.href = "/productTour/list";
                }else{
                    alert(result.msg);
                }
            }
        };
        let json_upload = "tour=" + JSON.stringify(_tour);
        http.send(json_upload+'&route='+_route);


    }

    //reindex
    function tableIndexing() {
        let table = document.getElementById("tour-details");
        let tableLength = table.getElementsByTagName("tr").length;
        for(let i = 0; i < tableLength; i++){
            if(i !== 0){
                table.rows[i].cells[1].innerHTML = i+'';
            }
        }
    }

});
