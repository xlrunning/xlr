$(document).ready(function() {

    // Default Data table
    if($(".datatable-default").length) {
	    $('.datatable-default').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Chinese.json"
            }
		});
	}


    // Default Data table
    if($(".datatable-roosa").length) {
	    $('.datatable-roosa').DataTable({
	    	language: {
	    		search: "",
	    		lengthMenu: "_MENU_",
	    		searchPlaceholder: "Search records...",
	    		paginate: {
			        first:      "<span class='fa fa-angle-double-left'></span>",
			        last:       "<span class='fa fa-angle-double-right'></span>",
			        next:       "<span class='fa fa-angle-right'></span>",
			        previous:   "<span class='fa fa-angle-left'></span>"
			    }
	    	},
	    	select: true,
			order: [],
			columnDefs: [ {
				targets  : 'no-sort',
				orderable: false,
			}],
	    	responsive: true,
	    });
	}

});