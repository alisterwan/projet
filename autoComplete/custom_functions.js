function lookup(inputString) {
if(inputString.length == 0) {
// Hide the suggestion box.
$('#suggestions').hide();
} 
else {
	$.post("rpc.php", {queryString: ""+inputString+""}, 
	function(data){
	if(data.length >0) {
	$('#suggestions').show();
	$('#autoSuggestionsList').html(data);}});
      }
} // lookup
	
function fill(thisValue) {
$('#inputString').val(thisValue);
setTimeout("$('#suggestions').hide();", 200);
}