<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="<?php print __CA_URL_ROOT__; ?>/app/plugins/rameau/jquery.suggest.js"></script>
<h2>Création de vedette matière Rameau</h2>
<div>
<fieldset id="buildyourform">
	<div class="fieldwrapper" id="field1"><input type="text" class="fieldname"><input type="button" class="remove" value="x"></div>
</fieldset>
<input type="button" value="+" class="add" id="add" />
</div>
<input type="button" value="Enregistrer" class="Enregistrer" id="add" />
<?php
?>
<script>

var haystack = ["ActionScript", "AppleScript", "Asp", "BASIC", "C", "C++", "Clojure", "COBOL", "ColdFusion", "Erlang", "Fortran", "Groovy", "Haskell", "Java", "JavaScript", "Lisp", "Perl", "PHP", "Python", "Ruby", "Scala", "Small Talk", "Scheme"];


$(document).ready(function() {
	$("input.fieldname").suggest(haystack,{});
	$("#add").click(function() {
			var lastField = $("#buildyourform div:last");
		var intId = (lastField && lastField.length && lastField.data("idx") + 1) || 2;
		var fieldWrapper = $("<div class=\"fieldwrapper\" id=\"field" + intId + "\"/>");
		fieldWrapper.data("idx", intId);
		var fName = $("<input type=\"text\" class=\"fieldname\" id=\"fieldname" + intId + "\"/>");
		var fType = $("<select class=\"fieldtype\"><option value=\"checkbox\">Checked</option><option value=\"textbox\">Text</option><option value=\"textarea\">Paragraph</option></select>");
		var removeButton = $("<input type=\"button\" class=\"remove\" value=\"x\" />");
		removeButton.click(function() {
			$(this).parent().remove();
		});
		fieldWrapper.append($("<span>&nbsp;--&nbsp;</span>"));
		fieldWrapper.append(fName);
		fieldWrapper.append(removeButton);
		$("#buildyourform").append(fieldWrapper);
		$("input#fieldname" + intId).suggest(haystack,{});
	});
	$("#preview").click(function() {
		$("#yourform").remove();
		var fieldSet = $("<fieldset id=\"yourform\"><legend>Your Form</legend></fieldset>");
		$("#buildyourform div").each(function() {
			var id = "input" + $(this).attr("id").replace("field","");
			var label = $("<label for=\"" + id + "\">" + $(this).find("input.fieldname").first().val() + "</label>");
			var input;
			switch ($(this).find("select.fieldtype").first().val()) {
				case "checkbox":
					input = $("<input type=\"checkbox\" id=\"" + id + "\" name=\"" + id + "\" />");
					break;
				case "textbox":
					input = $("<input type=\"text\" id=\"" + id + "\" name=\"" + id + "\" />");
					break;
				case "textarea":
					input = $("<textarea id=\"" + id + "\" name=\"" + id + "\" ></textarea>");
					break;
			}
			fieldSet.append(label);
			fieldSet.append(input);
		});
		$("body").append(fieldSet);
	});
});

</script>
<style>
fieldset {
	display:inline;
	width: auto;
	border:none;
}
.fieldwrapper,
.fieldwrapper div {
	display: inline-block;
}
input.remove {
	border: none;
	color:white;
	background-color: lightgray;
}
input.fieldname {
	padding: 2px;
	letter-spacing: 0;
	font-weight: normal;
	position: relative;
	color: #000;
	width: 200px;
	background: #fff;
	border:1px solid #bbb;
	z-index: 10;
}
</style>
