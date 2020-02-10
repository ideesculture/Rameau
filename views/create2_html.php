<script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>
  <iframe id="ici" src='/gestion/index.php/administrate/setup/list_item_editor/ListItemEditor/Edit/type_id/72348/item_id/0/parent_id/67483' style="display:none;">
</iframe>
<style>
	#ici {
		width:100%;
		height:100%;
		border:none;
		margin:0;
		padding:0;
		overflow: hidden;
	}
</style>	

<script>
	jQuery("document").ready(function() {
		window.setTimeout(
			function() {
				$("#ici").contents().find("#leftNav").hide();
				$("#ici").contents().find("#topNavContainer").hide();
				$("#ici").contents().find("#main").css("width","auto");
				$("#ici").contents().find("#mainContent").css("width","auto");
				$("#ici").contents().find("#mainContent").css("margin-left","0");
				$("#ici").contents().find("#mainContent").css("margin-top","0");
				$("#ici").contents().find("#mainContent").css("position","inherit");
				$("#ici").contents().find("#mainContent").css("border-right","none");
				$("#ici").contents().find("#mainContent > .control-box").hide();
				$("#ici").contents().find("body").css("overflow","hidden");
				$("#ici").show();
			},
			1000
		);
		
	});
	</script>