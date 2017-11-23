<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
AImporter::model ( 'cgroups' );

$model = new BookProModelCGroups ();
$state=$model->getState();
$state->set('filter.state',1);
$this->rates = $model->getItems ();

$db=JFactory::getDbo();
foreach ( $this->rates as $type ) {
	$query = $db->getQuery ( true );
	$query->select ( 'a.*' );
	$query->from ( '#__bookpro_roomrate AS a' );
	$query->innerJoin ( '#__bookpro_cgroup AS c ON c.id=a.pricetype' )->where('a.pricetype='.$type->id)->where('room_id='.$this->bustrip->id);
	$query->where('DATE_FORMAT(a.date,"%Y-%m-%d") = ' . $db->q($this->date));
	$db->setQuery($query);
	$price=$db->loadObject();
	$type->adult=null;
	$type->adult_roundtrip=null;
	$type->rate_id=null;
	if($price)
	{
		$type->adult=$price->adult;
		$type->adult_roundtrip=$price->adult_roundtrip;
		$type->rate_id=$price->id;
	}

}
AImporter::table('job');
$job=new TableJob($db);
$job->load(array('date'=>JFactory::getDate($this->date)->format('Y-m-d 00:00:00'),'route_id'=>$this->bustrip->id));

if($job->id){
	$seat=$job->seat;
	$driver_id=$job->cid;
}
else {
	$seat=$this->bustrip->seat;
	$driver_id=null;
}



?>

<table style="width: 250px">
	<tr>
		<td></td>
		<td><?php echo JText::_('COM_BOOKPRO_ONEWAY'); ?></td>
		<td><?php echo JText::_('COM_BOOKPRO_ROUNDTRIP'); ?></td>
	</tr>
	<?php
	
	for($i = 0; $i < count ( $this->rates ); $i ++) {
		
		?>

	<tr>

		<td><?php echo $this->rates[$i]->title?>
			<input type="hidden" value="<?php echo $this->rates[$i]->id ?>"	name="jform[<?php echo $i?>][pricetype]" />
			<input type="hidden" value="<?php echo $this->rates[$i]->rate_id ?>"	name="jform[<?php echo $i?>][id]" />
			<input type="hidden" value="<?php echo $this->date ?>"	name="jform[<?php echo $i?>][date]" />
			
			<input type="hidden" value="<?php echo $this->bustrip->id ?>" name="jform[<?php echo $i?>][room_id]" />
			
			</td>
		<td><input class="input-mini" type="text" name="jform[<?php echo $i?>][adult]" value="<?php echo $this->rates[$i]->adult ?>"
			required="required" /></td>
		<td><input class="input-mini" type="text" value="<?php echo $this->rates[$i]->adult_roundtrip ?>"
			name="jform[<?php echo $i?>][adult_roundtrip]" /></td>
	</tr>


<?php } ?>


	<tr>

		<td><?php echo JText::_('COM_BOOKPRO_SEAT'); ?>
			</td>
		<td><input class="input-mini" type="number" name="seat"	required="required" value="<?php echo $seat ?>"/></td>
		
	</tr>
	
		<tr>

		<td><?php echo JText::_('COM_BOOKPRO_DRIVER'); ?>
			</td>
		<td><?php echo $this->getDrivers($driver_id) ?></td>
		
	</tr>

</table>
<input type="hidden" value="<?php echo $job->id ?>"	name="job_id" />
<input type="hidden" value="<?php echo $this->date ?>"	name="date" />
<input type="hidden" value="<?php echo $this->bustrip->id ?>" name="room_id" />