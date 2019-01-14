$( function() 
{
    $("#language").selectmenu({
		change: function (event, data)
		{
			console.log(data.item.value);
			window.location.assign(data.item.value);
		}
	});
	
	$("#money").selectmenu();
	
	$(".cat_menu").accordion();
});