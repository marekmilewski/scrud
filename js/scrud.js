
function addColumn(){
    $.post(BASEURL+'scrud_admin/add_column', function(data) { $('#columns').append(data); } );
}


function addKey(){
    $.post(BASEURL+'scrud_admin/add_key', function(data) { $('#keys').append(data); } );
}


function addSearch(){
    $.post(BASEURL+'scrud_admin/add_search', function(data) { $('#searchs').append(data); } );
}

function addField(){
    $.post(BASEURL+'scrud_admin/add_field',function(data) { $('#fields').append(data); } );
}

function confirmDialog(link){
    if (confirm('Na pewno wykonać?'))
        document.location.href=link;
}

function deleteColumn(scrudID,columnID){
    if(scrudID!='')
        $.post(BASEURL+'scrud_admin/delete_column',  {'scrudID':scrudID, 'columnID':columnID}, function(data) { } );
    
    $('#col-a'+columnID).remove();
    $('#col-b'+columnID).remove();
     
}

function deleteKey(scrudID,keyID){
    if(scrudID!='')
        $.post(BASEURL+'scrud_admin/delete_key',  {'scrudID':scrudID, 'keyID':keyID}, function(data) {} );
    
    $('#key'+keyID).remove();
}

function deleteSearch(scrudID,searchID){
    if(scrudID!='')
        $.post(BASEURL+'scrud_admin/delete_search',  {'scrudID':scrudID, 'searchID':searchID}, function(data) {} );
    
    $('#search'+searchID).remove();
}

function deleteField(scrudID,fieldID){
    if(scrudID!='')
        $.post(BASEURL+'scrud_admin/delete_field',  {'scrudID':scrudID, 'fieldID':fieldID}, function(data) {} );
    
    $('#field-a'+fieldID).remove();
    $('#field-b'+fieldID).remove();
}