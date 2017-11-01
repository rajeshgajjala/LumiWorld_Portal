function getAllEnterpriseMessages(enterpriseId, perPage, currentPage, callback){
    $.ajax({
        type: "GET",
        url: "http://" + serverURL + ":12003/getnewsfeedbyenterpriseid?id=" + oEnterprise.id + "&limit=" + perPage + "&page=" + currentPage,
        contentType: "application/x-www-form-urlencoded",
        dataType: "json",
        success: function (result) {
            callback(result);
        },
        error: function (e) {
            console.log(e);
        }
    });
}

function createNode(node, callback, fail){
    $.ajax({
        type: "POST",
        url: "http://" + serverURL + ":12100/createuniquenode", 
        data: {
            params: node
        }, 
        contentType: "application/x-www-form-urlencoded",
        dataType: "json",
        success: callback,
        error: fail
    });
}

function createRelationship(relationship, callback, fail){
    $.ajax({
        type: "POST",
        url: "http://" + serverURL + ":12103/createRelationship",
        data: {
            params: relationship
        },
        contentType: "application/x-www-form-urlencoded",
        dataType: "json",
        success: callback,
        error: fail
    });
}

function addRelationshipProperties(relationship, callback, fail){
    if (typeof (relationship) !== 'string')
        relationship = JSON.stringify(relationship);
    console.log(relationship);
    $.ajax({
        type: "POST",
        url: "http://" + serverURL + ":12108/addrelationshippropertiesbyid",
        data: {
            params: relationship
        },
        contentType: "application/x-www-form-urlencoded",
        dataType: "json",
        success: callback,
        error: fail
    });
}

function addNodeProperties(node, callback, fail){
    if (typeof (node) !== 'string')
        node = JSON.stringify(node);
    $.ajax({
        type: "POST",
        url: "http://" + serverURL + ":12101/addnodeproperties",
        data: {
            params: node
        },
        contentType: "application/x-www-form-urlencoded",
        dataType: "json",
        success: callback,
        error: fail
    });
}