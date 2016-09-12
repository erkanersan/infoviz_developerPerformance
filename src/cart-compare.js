 function addDeveloperToCartDevelopers(element, userid, cartcount) {
        $.ajax({
                method: "POST",
                url: "/infovis/cart-add-developer.php",
                data: { id: userid }
        })
                .done(function( msg ) {
                //alert( "SERVER MESSAGE: \n\n" + msg );
                document.getElementById("img"+userid).src = "images/cart-added.gif";
                document.getElementById("anchor"+userid).href = "javascript:removeDeveloperFromCartDevelopers(this, " + userid + ")";
                document.getElementById("buttoncompare").innerHTML = "COMPARE (" + msg + ")";
		document.getElementById("buttonclear").style.display = 'inline';
		if (msg > 1)
			document.getElementById("buttoncompare").style.display = 'inline';
        });
 } 

 function removeDeveloperFromCartDevelopers(element, userid) {
        $.ajax({
                method: "POST",
                url: "/infovis/cart-remove-developer.php",
                data: { id: userid }
        })
                .done(function( msg ) {
                //alert( "SERVER MESSAGE: \n\n" + msg );
                document.getElementById("img"+userid).src = "images/cart-removed.gif";
                document.getElementById("anchor"+userid).href = "javascript:addDeveloperToCartDevelopers(this, " + userid + ")";
                document.getElementById("buttoncompare").innerHTML = "COMPARE (" + msg + ")";
		if (msg<2) {
			document.getElementById("buttoncompare").style.display = 'none';
		}
		if (msg<1) {
                        document.getElementById("buttonclear").style.display = 'none';
                }
        });
 }
 

 function addAllProjectDevelopersToCartDevelopers(projectid) {
        $.ajax({
                method: "POST",
                url: "/infovis/cart-add-all-projectdevelopers.php",
                data: { id: projectid }
        })
                .done(function( msg ) {
                //alert( "The Project's Developers have been added into the Cart (" + msg + ")" );
		location.reload();
        });
 }


 function removeAllProjectDevelopersFromCartDevelopers(projectid) {
        $.ajax({
                method: "POST",
                url: "/infovis/cart-remove-all-projectdevelopers.php",
                data: { id: projectid }
        })
                .done(function( msg ) {
                //alert( "The Project's Developers have been removed from the Cart (" + msg + ")" );
                location.reload();
        });
 }


 function clearCartDevelopers() {
        $.ajax({
                method: "POST",
                url: "/infovis/cart-clear-developer.php",
                data: { name: "John", location: "Boston" }
        })
                .done(function( msg ) {
                //alert( "SERVER MESSAGE: \n\n" + msg );
                location.reload();
                document.getElementById("buttoncompare").style.display = 'none';
        });
 }
 
 function compareDevelopers() {
	//window.open("/infovis/developer_compare.php");
 	window.parent.location.href="/infovis/developer_compare.php";	
 }
