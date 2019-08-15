
$(function () {
	'use strict';
	var nhlTeams = [ "Deutsch",
	"Englisch",
	"Informationstechnik",
	"Chemie",
	"GGK",
	"Geschichte",
	"Computertechnik",
	"CT",
	"IT",
	"Französisch",
	"Spanisch",
	"Italienisch",
	"Physik",
	"Mathematik",
	"Wirtschaft",
	"Religion Evangelisch",
	"Religion Katholisch",
	"Ethik",
	"Sport",
	"Frei",
	"Maschinentechnik",
	"Elektrotechnik",];
	var nhl = $.map(nhlTeams, function (team) { return { value: team, data: { category: 'Fächer' }}; });
	var Faecher = nhl;

	// Initialize autocomplete with local lookup:
	$('#autocomplete').devbridgeAutocomplete({
		lookup: Faecher,
		minChars: 1,
		onSelect: function (suggestion) {
			$('#selection').html('You selected: ' + suggestion.value + ', ' + suggestion.data.category);
		},
		showNoSuggestionNotice: false,
		noSuggestionNotice: 'Nichts gefunden',
		groupBy: 'category'
	});
	
});
	