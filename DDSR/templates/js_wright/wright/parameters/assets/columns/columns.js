window.addEvent('load', function() {
	checkColumns();
	$$('select.columns').addEvent('change', function() {
		changeColumns();
	});
});

function changeColumns() {
	checkColumns();
	setColumnParam();
}

function setColumnParam() {
	var widths = new Array();
	$$('div.col').each(function(column){
		widths.push(column.getProperty('id').substring(7)+':'+column.getElement('select').getProperty('value'));
	});

	$('jform[params][columns]').setProperty('value', widths.join(';'));
}

function checkColumns() {
	var widths = new Number(0);
	$$('select.columns').each(function(column){
		widths += parseInt(column.getProperty('value'));
	});
	$('columns_used').set('text', widths);
	if (widths !== 12)
	{
		$('column_info').setStyle('color', 'red');
		$('columns_warning').setStyle('display', 'inline');
	}
	else
	{
		$('column_info').setStyle('color', 'inherit');
		$('columns_warning').setStyle('display', 'none');
	}
	$$('div.col').each(function(column){
		column.removeClass('span1');
		column.removeClass('span2');
		column.removeClass('span3');
		column.removeClass('span4');
		column.removeClass('span5');
		column.removeClass('span6');
		column.removeClass('span7');
		column.removeClass('span8');
		column.removeClass('span9');
		column.removeClass('span10');
		column.removeClass('span11');
		column.removeClass('span12');
		column.addClass('span' + column.getElement('select').getProperty('value'));
	});
}

function swapColumns(col, dir) {
	var cols = $$('div.col');
	var index = 0;
	var selected = 'column_'+col;
	if (dir == 'right')
	{
		cols.each(function(el) {
			if (el.getProperty('id') == selected)
			{
				swapindex = index + 1;
			}
			index++;
		});
		$(selected).inject(cols[swapindex],'after');
	}
	else
	{
		cols.each(function(el) {
			if (el.getProperty('id') == selected)
			{
				swapindex = index - 1;
			}
			index++;
		});
		$(selected).inject(cols[swapindex],'before');
	}
	checkColumns();
	setColumnParam();
}
