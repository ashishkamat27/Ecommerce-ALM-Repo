<?php
include('Helper_page.php');
include "html/adminHeader.html";
$tbl_name="product_details";		
$adjacents = 3;
$query =$helper->read_all("COUNT(*)","product_details");
    if(is_array($query))
    {
       foreach($query as $row)
       {
       $total_pages = $row['COUNT(*)'];
       }
    }
$targetpage = "ProductViewPage.php"; 	//your file name  (the name of this file)
$limit = 3; 								//how many items to show per page
$page = $_REQUEST['page'];
if($page) 
$start = ($page - 1) * $limit; 			//first item to display on this page
else
$start = 0;								//if no page var is given, set start to 0
?>
<div class="container">
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">
<div class="table-responsive">

<?php 
echo "<table align='left' width=90% border='1'class='table table-striped'>";
?>

<tr>
<th width="80" >Image</th>
<th width="60" >Product Name</th>
<th width="30" >Price</th>
<th width="80">Description</th>
<th width="30" >Product ID</th>
<th width="10" >Select</th>
<br/>
</tr>

<?php
$sql =$helper_page->read_page("*","product_details","LIMIT $start,$limit");

   if(is_array($sql))
   {
      foreach($sql as $row)
      {
      $product_id=$row['product_id'];
?>

       <td width="80" ><img src='<?=$row["image_path"]?>' width=150px height=150px></td> <!-- columns can have both text and images -->
       <td width="60" ><b><?=$row['product_name'] ?></b></td>
       <td width="30" ><?=$row['price'] ?>              </td>
       <td width="80" ><?=$row['description'] ?>        </td>
       <td width="30" ><?=$row['product_id'] ?>         </td>
       <td width="80" >
       <input type="button" value="DELETE" onClick='delete_product("<?=$row['product_id'] ?>")'/>
       <input type="button" value="UPDATE" onClick='update_product("<?=$row['product_id'] ?>")'/>
       </td>
       <input type="hidden" name="page" value="2" />	<br/>	
       </tr>
	   
<?php     
    }
echo "</table>";						
   }	

/* Setup page vars for display. */
if ($page == 0) 
$page = 1;					//if no page var is given, default to 1.
$prev = $page - 1;							//previous page is page - 1
$next = $page + 1;							//next page is page + 1
$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
$lpm1 = $lastpage - 1;						//last page minus 1	
/* 
Now we apply our rules and draw the pagination object. 
We're actually saving the code to a variable in case we want to draw it more than once.
*/
$pagination = "";
if($lastpage > 1)
{	
$pagination .= "
<center>
<div class=\"pagination\">";
//previous button
if ($page > 1) 
$pagination.= "<a href=\"$targetpage?page=$prev\">previous</a>";
else
$pagination.= "<span class=\"disabled\">previous</span>";	
//pages	
if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
{	
for ($counter = 1; $counter <= $lastpage; $counter++)
{
if ($counter == $page)
$pagination.= "<span class=\"current\">$counter</span>";
else
$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
}
}
elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
{
//close to beginning; only hide later pages
if($page < 1 + ($adjacents * 2))		
{
for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
{
if ($counter == $page)
$pagination.= "<span class=\"current\">$counter</span>";
else
$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
}
$pagination.= "...";
$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
}
//in middle; hide some front and some back
elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
{
$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
$pagination.= "...";
for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
{
if ($counter == $page)
$pagination.= "<span class=\"current\">$counter</span>";
else
$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
}
$pagination.= "...";
$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
}
//close to end; only hide early pages
else
{
$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
$pagination.= "...";
for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
{
if ($counter == $page)
$pagination.= "<span class=\"current\">$counter</span>";
else
$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
}
}
}
//next button
if ($page < $counter - 1) 
$pagination.= "<a href=\"$targetpage?page=$next\">next </a>";
else
$pagination.= "<span class=\"disabled\">next</span>";
$pagination.= "</div></center> </div>";		
}
?>
</div>
</div>
<?=$pagination?>
<?php 
include "html/adminNavigation.html";
?>