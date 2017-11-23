<div id="prefetch">
  <input class="typeaheadjb" type="text" placeholder="Countries">
</div>
<script type="text/javascript">
<!--

//-->
jQuery.fn.bootstrapTypeahead = jQuery.fn.typeahead.noConflict();
var countries = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.whitespace,
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  // url points to a json file that contains an array of country names, see
  // https://github.com/twitter/typeahead.js/blob/gh-pages/data/countries.json
  prefetch: 'http://localhost/data/countries.json'
});

// passing in `null` for the `options` arguments will result in the default
// options being used
jQuery('#prefetch .typeaheadjb').typeahead(null, {
  name: 'countries',
  source: countries
});

</script>