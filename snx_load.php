
!DOCTYPE html>

<html>

<head>

<style type='text/css'>

  .main .dygraph-legend > span { display: none; }

  .main .dygraph-legend > span.highlight { display: inline; }



  button {

    background-color: Transparent;

    background-repeat:no-repeat;

    border: none;

    cursor:pointer;

    overflow: hidden;

    outline:none;

}

</style>

<title>load_1m Data</title>

</head>

<body>

<table width="100%" border="0">

<tr>

 <td colspan="2" bgcolor="ffffff">
 <div data-role="page">
        <div data-role="header" data-position="fixed">

      <h1 align = "center"> <span style = "font-family:Open Sans; font-weight:300; color:#1a8cff; position:absolute;top:10px;left:430px "> Load Data for File System Nodes </h1>
      </br>
      <div class="aligncenter" style="width:1010px;margin-bottom:-12.5px;"><hr /></div>

    </div>
    </div>
 </td>

  <td>
                <img src="NCSA.jpg" alt="NCSA icon" style="position:absolute;top:-4px;left:1030px;width:80px;height:60px;float:left">

      <img src="I.jpg" alt="I icon" style="position:absolute;top:-4px;left:1140px;width:80px;height:60px;float:right">

 </td>
</tr>



  <tr valign="top" height="200" style="overflow:auto;">

    <td bgcolor="#aaa" width="17%">

      <h4 align="center"><u>File System</u></h4>

          <br />

                  <?php

require_once('/var/www/html/ConnSSL.php');
if (mysqli_connect_errno()) {
    printf("DB error: %s", mysqli_connect_error());
    exit();
}
mysqli_select_db($db, '****') or die('Could not select database');

##                              $dbhost = '****';

##                              $dbuser = '****';

##                              $dbpass = '****';

##                              $db = mysql_connect($dbhost, $dbuser, $dbpass);

##                              if(! $db ) {

##                                      die('Could not connect: ' . mysql_error());

##                              }

                                $sql = 'SELECT distinct FileSystem FROM storage_lustre_node_load where (utime <= UNIX_TIMESTAMP( NOW()) AND utime >= UNIX_TIMESTAMP( DATE_ADD(NOW(), INTERVAL -1 DAY))) order by FileSystem,node';

##                              mysql_select_db('****');

##                              $retval = mysql_query( $sql, $db );
                                $retval = mysqli_query( $db, $sql);

                                if(! $retval ) {

                                        die('Could not get data: ' . mysql_error());

                                }

                                while($row = mysqli_fetch_array($retval, MYSQL_NUM)) {

                                        echo " <input type=\"button\" value=\"SNX1100".$row[0]."\" onclick=\"return change(this);\" /><br/><br/><br/>";

                                }

##                              mysql_close($db);

                ?>
<script type="text/javascript">

function change( el )

{

    var table = document.getElementById("CSVTable");

        table.style.display = "table" ;



        var valRet= el.value;

    var fileSystem= "";

    //if ( valRet === "FS-2" )

        //{

    //    fileSystem = "2";

    //}

        //else if( valRet === "FS-3")

    //{

        //      fileSystem = "3";

        //}//mystring = mystring.replace('/r','/');

        fileSystem=valRet.replace('SNX1100','');

        var table = document.getElementById("CSVTable");

    var rows = table.getElementsByTagName("tr");

         var ar=[];

        for (i = 1; i < rows.length; i++) {

            var currentRow = rows[i];

        var cell = currentRow.getElementsByTagName("td")[0];

        var rowfs = cell.innerHTML;

                if(rowfs === fileSystem)

                {
                        ar[i-1] = true;
                        currentRow.style.display = "table-row" ;

                }
  else

                {

                        ar[i-1] = false;
                        currentRow.style.display = "none" ;

                }

    }
        g2.updateOptions({
                visibility: ar,
                title: "SNX1100"+fileSystem
        });

                          <?php

                                $statsData=array();

##                              $dbhost = '****';

##                              $dbuser = '****';

##                              $dbpass = '****';

##                              $db = mysql_connect($dbhost, $dbuser, $dbpass);

##                              if(! $db ) {

##                                      die('Could not connect: ' . mysql_error());

##                              }

                                #SELECT FileSystem,COUNT(load_1m), SUM(load_1m), AVG(load_1m),STDDEV_SAMP(load_1m) ,VAR_SAMP(load_1m),MIN(load_1m),MAX(load_1m) FROM storage_lustre_node_load group by FileSystem;

                                $sql = 'SELECT FileSystem,COUNT(load_1m), SUM(load_1m), AVG(load_1m),STDDEV_SAMP(load_1m) ,VAR_SAMP(load_1m),MIN(load_1m),MAX(load_1m) FROM storage_lustre_node_load where (utime <= UNIX_TIMESTAMP( NOW()) AND utime >= UNIX_TIMESTAMP( DATE_ADD(NOW(), INTERVAL -1 DAY))) group by FileSystem';

##                              mysql_select_db('****');

                                $retval = mysqli_query( $db, $sql );

                                if(! $retval ) {

                                        die('Could not get data: ' . mysqli_error());

                                }

                                while($row = mysqli_fetch_array($retval, MYSQL_NUM)) {

                                    $statsData[] = $row;

                                        #echo " <input type=\"button\" value=\"FS-".$row[0]."\" onclick=\"return change(this);\" /><br/>";

                                }

##                              mysql_close($db);
                    ?>



                        var jArray= <?php echo json_encode($statsData ); ?>;



                        for(var i=0;i<jArray.length;i++){

                               rowArray=jArray[i];

                                   if(rowArray[0]  === fileSystem)

                                   {

                                                document.getElementById("NObservations").innerHTML = rowArray[1];

                                                document.getElementById("SumObservations").innerHTML = rowArray[2];

                                                document.getElementById("MeanObservations").innerHTML = rowArray[3];

                                                document.getElementById("SDObservations").innerHTML = rowArray[4];

                                                document.getElementById("VarianceObservations").innerHTML = rowArray[5];

                                                document.getElementById("MinObservations").innerHTML = rowArray[6];

                                                document.getElementById("MaxObservations").innerHTML = rowArray[7];

                                   }

                        }



                                                                        <?php

                                                $outlierData=array();

##                                              $dbhost = '****';

##                                              $dbuser = '****';

##                                              $dbpass = '****s';

##                                              $db = mysql_connect($dbhost, $dbuser, $dbpass);

##                                              if(! $db ) {

##                                                      die('Could not connect: ' . mysql_error());

##                                              }

                                                $sql = 'SELECT FileSystem,node,load_1m,utime FROM storage_lustre_node_load where load_1m>100 and (utime <= UNIX_TIMESTAMP( NOW()) AND utime >= UNIX_TIMESTAMP( DATE_ADD(NOW(), INTERVAL -1 DAY))) order by load_1m DESC';

##                                              mysql_select_db('****');

                                                $retval = mysqli_query( $db, $sql );
                          if(! $retval ) {

                                                        die('Could not get data: ' . mysql_error());

                                                }

                                                while($row = mysqli_fetch_array($retval, MYSQL_NUM)) {

                                                        $outlierData[] = $row;

                                                        #echo " <input type=\"button\" value=\"FS-".$row[0]."\" onclick=\"return change(this);\" /><br/>";

                                                }

##                                              mysql_close($db);

                                                ?>

                                        var jArray= <?php echo json_encode($outlierData ); ?>;

                                                var outliers="";

                                                var outliersCount= jArray.length;

                                                var fileSystemOutliers=0;

                                                /*if(outliersCount>10)

                                                {

                                                        outliersCount=10;

                                                        outliers="Alert: recieved more than 10 outliers, so displaying only the top ten results&#13;&#10;";

                                                }*/

                                                for(var i=0;i<outliersCount;i++){

                                                        rowArray=jArray[i];

                                                        if(rowArray[0] === fileSystem)

                                                        {
                                                        var my_timestamp = rowArray[3];
                                                        var my_date = new Date(my_timestamp*1000);
                                                        var my_iso = my_date.toISOString().replace(/T/,' ');//.match(/(\d{2}:\d{2}:\d{2})/)
                                                        my_iso = my_iso.replace(/Z/,' ').slice(0,19);//.match(/(\d{2}:\d{2}:\d{2})/)
                                                        //var my_iso = my_date.toISOString().match(/(\d\d:\d\d:\d\d)/);

                                                        outliers=outliers +"FileSystem:"+rowArray[0]+"&#13;&#10;"+"Node:"+rowArray[1]+"&#13;&#10;"+"load:"+rowArray[2]+"&#13;&#10;"+"utime:"+my_iso+"&#13;&#10;&#13;&#10;";

                                                        fileSystemOutliers++;

                                                        }

                                                     if(fileSystemOutliers==10)

                                                        {

                                                                        outliers="Alert: recieved more than 10 outliers, so displaying only the top ten results&#13;&#10;"+outliers;

                                                                        break;

                                                        }

                                                }

                        document.getElementById("outlierArea").innerHTML = outliers;

                                                if(fileSystemOutliers == 0)

                                                {

                                                        document.getElementById("outlierArea").innerHTML = "No Outliers found!!!";

                                                }

}

</script>

    </td>

    <td bgcolor="ffffff" height="200" width="60%">

        <div id="graphdiv2" class="main" style ="height:300px" >

                </div>

    </td>

    <td bgcolor="#aaa" width="20%" height="200" style="overflow:auto;">

      <div height="200" style="width:100%; height:100%; overflow:auto;">

          <table id="CSVTable" height="200" class="table-fill" style="display: none;  overflow:auto;">

          <tr><th>File System</th><th>Node</th></tr>

          <?php

##                              $dbhost = '****';

##                              $dbuser = '****';

##                              $dbpass = '****';

##                              $db = mysql_connect($dbhost, $dbuser, $dbpass);

##                              if(! $db ) {

##                                      die('Could not connect: ' . mysql_error());

##                              }
  $sql = 'SELECT distinct FileSystem,node FROM storage_lustre_node_load  where (utime <= UNIX_TIMESTAMP( NOW()) AND utime >= UNIX_TIMESTAMP( DATE_ADD(NOW(), INTERVAL -1 DAY)))  order by FileSystem,node';

##                              mysql_select_db('****');

                                $retval = mysqli_query( $db, $sql);

                                if(! $retval ) {

                                        die('Could not get data: ' . mysqli_error());

                                }

                                while($row = mysqli_fetch_array($retval, MYSQL_NUM)) {

                                        echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";

                                }

##                              mysql_close($db);

                ?>

          </table>

          </div>

    </td>

   </tr>

<tr>

 <td colspan="2" bgcolor="#aaa">

    <div height="800" style="width:100%; height:100%; overflow:auto;">

        <h3 align="center"><u>Basic Descriptive Statistics of Graph</u></h3>
        <div style="float: left; width: 50%;">

        Number of Observations: <b><p id="NObservations">---</p> </b>

        <br />

        Sum of Observations: <b><p id="SumObservations">---</p> </b>

        <br />

        Mean: <b><p id="MeanObservations">---</p> </b>

        <br />

        Standard Deviation:<b><p id="SDObservations">---</p> </b>
        </div>
        <div style="float: left; width: 50%;">

        <br />

        Variance:<b><p id="VarianceObservations">---</p> </b>
 <br />

        Minimum value:<b><p id="MinObservations">---</p> </b>

        <br />

        Maximum value:<b><p id="MaxObservations">---</p> </b>

        <br />
        </div>

        </div>

 </td>



  <td colspan="2" bgcolor="#aaa">
    <div height="800" style="width:100%; height:100%; overflow:auto;">

        <h3> <span style = "position:absolute; top:370px;left:1090px"><u>Outliers</u></h3>
        </br>

                <textarea height="720" style="font-family:Times New Roman; width:90%; height:90%; overflow:auto;" id="outlierArea" ></textarea>

        </div>

        </td>

</tr>

</table>

<script type="text/javascript">

function myfunction() {



    var table = document.getElementById("CSVTable");

    var rows = table.getElementsByTagName("tr");



        for (i = 0; i < rows.length; i++) {

            var currentRow = table.rows[i];

        var createClickHandler =

            function(row,x)

            {

                return function() {

                                                                                var ar=[];

                                                                                        for (i = 0; i < rows.length; i++) {

                                                                                        ar.push(false);

                                                                                }

                                                                                var FileSysNum = row.getElementsByTagName("td")[0].innerHTML;

                                                                                var NodeNum = row.getElementsByTagName("td")[1].innerHTML;

                                        var cell = row.getElementsByTagName("td")[0];

                                        var id = cell.innerHTML;

                                                                                ar[x-1] = true;

                                                                                                g2.updateOptions({

                                                                                                        visibility: ar,

                                                                                                        title: "SNX1100"+FileSysNum+"N00"+NodeNum

                                                                                                });



                                                                                document.getElementById("graphdiv2").style.pointerEvents = "auto";



                                                                                <?php

                                                                                                $statsData=array();

##                                                                                              $dbhost = '****';

##                                                                                              $dbuser = '****';

##                                                                                              $dbpass = '****';

##                                                                                              $db = mysql_connect($dbhost, $dbuser, $dbpass);

##                                                                                              if(! $db ) {
##                                                                                                      die('Could not connect: ' . mysql_error());

##                                                                                              }

                                                                                                #SELECT FileSystem,COUNT(load_1m), SUM(load_1m), AVG(load_1m),STDDEV_SAMP(load_1m) ,VAR_SAMP(load_1m),MIN(load_1m),MAX(load_1m) FROM storage_lustre_node_load group by FileSystem;

                                                                                                $sql = 'SELECT FileSystem,node,COUNT(load_1m), SUM(load_1m), AVG(load_1m),STDDEV_SAMP(load_1m) ,VAR_SAMP(load_1m),MIN(load_1m),MAX(load_1m) FROM storage_lustre_node_load where (utime <= UNIX_TIMESTAMP( NOW()) AND utime >= UNIX_TIMESTAMP( DATE_ADD(NOW(), INTERVAL -1 DAY))) group by FileSystem,node';

##                                                                                              mysql_select_db('****');

                                                                                                $retval = mysqli_query( $db, $sql);

                                                                                                if(! $retval ) {

                                                                                                        die('Could not get data: ' . mysqli_error());

                                                                                                }

                                                                                                while($row = mysqli_fetch_array($retval, MYSQL_NUM)) {

                                                                                                        $statsData[] = $row;

                                                                                                        #echo " <input type=\"button\" value=\"FS-".$row[0]."\" onclick=\"return change(this);\" /><br/>";

                                                                                                }

##                                                                                              mysql_close($db);

                                                                                        ?>



                                                                                        var jArray= <?php echo json_encode($statsData ); ?>;

                                                                                        for(var i=0;i<jArray.length;i++)

                                                                                        {

                                                                                           rowArray=jArray[i];

                                                                                           if(rowArray[0]  === row.getElementsByTagName("td")[0].innerHTML && rowArray[1]  === row.getElementsByTagName("td")[1].innerHTML)

                                                                                           {

                                                                                                        document.getElementById("NObservations").innerHTML = rowArray[2];

                                                                                                        document.getElementById("SumObservations").innerHTML = rowArray[3];
 document.getElementById("MeanObservations").innerHTML = rowArray[4];

                                                                                                        document.getElementById("SDObservations").innerHTML = rowArray[5];

                                                                                                        document.getElementById("VarianceObservations").innerHTML = rowArray[6];

                                                                                                        document.getElementById("MinObservations").innerHTML = rowArray[7];

                                                                                                        document.getElementById("MaxObservations").innerHTML = rowArray[8];

                                                                                          }

                                                                                        }



                                                                                                        <?php

                                                $outlierData=array();

##                                              $dbhost = '****';

##                                              $dbuser = '****';

##                                              $dbpass = '****';

##                                              $db = mysql_connect($dbhost, $dbuser, $dbpass);

##                                              if(! $db ) {

##                                                      die('Could not connect: ' . mysql_error());

##                                              }

                                                $sql = 'SELECT FileSystem,node,load_1m,utime FROM storage_lustre_node_load where load_1m>100 and (utime <= UNIX_TIMESTAMP( NOW()) AND utime >= UNIX_TIMESTAMP( DATE_ADD(NOW(), INTERVAL -1 DAY))) order by load_1m DESC';

##                                                      mysql_select_db('****');

                                                $retval = mysqli_query( $db, $sql );

                                                if(! $retval ) {

                                                        die('Could not get data: ' . mysqli_error());

                                                }

                                                while($row = mysqli_fetch_array($retval, MYSQL_NUM)) {

                                                        $outlierData[] = $row;

                                                        #echo " <input type=\"button\" value=\"FS-".$row[0]."\" onclick=\"return change(this);\" /><br/>";

                                                }
##                                              mysql_close($db);

                                                ?>

                                        var jArray= <?php echo json_encode($outlierData ); ?>;

                                                var outliers="";

                                                var outliersCount= jArray.length;

                                                var fileSystemOutliers=0;

                                                /*if(outliersCount>10)

                                                {

                                                        outliersCount=10;

                                                        outliers="Alert: recieved more than 10 outliers, so displaying only the top ten results&#13;&#10;";

                                                }*/

                                                for(var i=0;i<outliersCount;i++){

                                                        rowArray=jArray[i];

                                                        if(rowArray[0]  === row.getElementsByTagName("td")[0].innerHTML && rowArray[1]  === row.getElementsByTagName("td")[1].innerHTML)

                                                        {
                                                        var my_timestamp = rowArray[3];
                                                        var my_date = new Date(my_timestamp*1000);
                                                        var my_iso = my_date.toISOString().replace(/T/,' ');//.match(/(\d{2}:\d{2}:\d{2})/)
                                                        my_iso = my_iso.replace(/Z/,' ').slice(0,19);//.match(/(\d{2}:\d{2}:\d{2})/)

                                                        outliers=outliers +"FileSystem:"+rowArray[0]+"&#13;&#10;"+"Node:"+rowArray[1]+"&#13;&#10;"+"load:"+rowArray[2]+"&#13;&#10;"+"utime:"+my_iso+"&#13;&#10;&#13;&#10;";

                                                        fileSystemOutliers++;

                                                        }

                                                        if(fileSystemOutliers==10)

                                                        {

                                                                        outliers="Alert: recieved more than 10 outliers, so displaying only the top ten results&#13;&#10;"+outliers;

                                                                        break;

                                                        }

                                                }

                        document.getElementById("outlierArea").innerHTML = outliers;

                                                if(fileSystemOutliers == 0)
 {

                                                        document.getElementById("outlierArea").innerHTML = "No Outliers found!!!";

                                                }



                        };

            };

        currentRow.onclick = createClickHandler(currentRow,i);

    }

}

myfunction();

</script>







<script type="text/javascript"

   src="//cdnjs.cloudflare.com/ajax/libs/dygraph/1.1.1/dygraph-combined.js"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script src="./jquery.csvToTable.js"></script>

<script type="text/javascript">

$(function() {

                                        <?php

                                                $statsData=array();

##                                              $dbhost = '****';
##
##                                              $dbuser = '****';
##
##                                              $dbpass = '****';
##
##                                              $db = mysql_connect($dbhost, $dbuser, $dbpass);
##
##                                              if(! $db ) {
##
##                                                      die('Could not connect: ' . mysql_error());
##
##                                              }

 #$sql = 'SELECT FileSystem,COUNT(load_1m), SUM(load_1m), AVG(load_1m),STDDEV_SAMP(load_1m) ,VAR_SAMP(load_1m),MIN(load_1m),MAX(load_1m) FROM storage_lustre_node_load where (utime <= UNIX_TIMESTAMP( NOW()) AND utime >= UNIX_TIMESTAMP( DATE_ADD(NOW(), INTERVAL -1 DAY))) group by FileSystem';



                                                $sql = 'SELECT COUNT(load_1m), SUM(load_1m), AVG(load_1m),STDDEV_SAMP(load_1m) ,VAR_SAMP(load_1m),MIN(load_1m),MAX(load_1m) FROM storage_lustre_node_load where (utime <= UNIX_TIMESTAMP( NOW()) AND utime >= UNIX_TIMESTAMP( DATE_ADD(NOW(), INTERVAL -1 DAY))) ';

##                                              mysql_select_db('****');

                                                $retval = mysqli_query( $db, $sql );

                                                if(! $retval ) {

                                                        die('Could not get data: ' . mysql_error());

                                                }

                                                while($row = mysqli_fetch_array($retval, MYSQL_NUM)) {

                                                        $statsData[] = $row;

                                                        #echo " <input type=\"button\" value=\"FS-".$row[0]."\" onclick=\"return change(this);\" /><br/>";

                                                }

##                                              mysql_close($db);

                                                ?>



                                                var jArray= <?php echo json_encode($statsData ); ?>;

                                                   rowArray=jArray[0];

                                                        document.getElementById("NObservations").innerHTML = rowArray[0];

                                                        document.getElementById("SumObservations").innerHTML = rowArray[1];

                                                        document.getElementById("MeanObservations").innerHTML = rowArray[2];

                                                        document.getElementById("SDObservations").innerHTML = rowArray[3];

                                                        document.getElementById("VarianceObservations").innerHTML = rowArray[4];

                                                        document.getElementById("MinObservations").innerHTML = rowArray[5];

                                                        document.getElementById("MaxObservations").innerHTML = rowArray[6];





                                                <?php
  $outlierData=array();

##                                              $dbhost = '****';

##                                              $dbuser = '****';

##                                              $dbpass = '****';

##                                              $db = mysql_connect($dbhost, $dbuser, $dbpass);

##                                              if(! $db ) {

##                                                      die('Could not connect: ' . mysql_error());

##                                              }

                                                $sql = 'SELECT FileSystem,node,load_1m,utime FROM storage_lustre_node_load where load_1m>100 and (utime <= UNIX_TIMESTAMP( NOW()) AND utime >= UNIX_TIMESTAMP( DATE_ADD(NOW(), INTERVAL -1 DAY))) order by load_1m DESC';

##                                              mysql_select_db('****');

                                                $retval = mysqli_query( $db, $sql );

                                                if(! $retval ) {

                                                        die('Could not get data: ' . mysql_error());

                                                }

                                                while($row = mysqli_fetch_array($retval, MYSQL_NUM)) {

                                                        $outlierData[] = $row;

                                                        #echo " <input type=\"button\" value=\"FS-".$row[0]."\" onclick=\"return change(this);\" /><br/>";

                                                }

##                                              mysql_close($db);

                                                ?>

                                        var jArray= <?php echo json_encode($outlierData ); ?>;

                                                var outliers="";

                                                var outliersCount= jArray.length;

                                                var fileSystemOutliers=0;

                                                /*if(outliersCount>10)

                                                {

                                                        outliersCount=10;

                                                        outliers="Alert: recieved more than 10 outliers, so displaying only the top ten results&#13;&#10;";
 }*/

                                                for(var i=0;i<outliersCount;i++){

                                                        rowArray=jArray[i];
                                                        var my_timestamp = rowArray[3];
                                                        var my_date = new Date(my_timestamp*1000);
                                                        var my_iso = my_date.toISOString().replace(/T/,' ');//.match(/(\d{2}:\d{2}:\d{2})/)
                                                        my_iso = my_iso.replace(/Z/,' ').slice(0,19);//.match(/(\d{2}:\d{2}:\d{2})/)


                                                        outliers=outliers +"FileSystem:"+rowArray[0]+"&#13;&#10;"+"Node:"+rowArray[1]+"&#13;&#10;"+"load:"+rowArray[2]+"&#13;&#10;"+"utime:"+my_iso+"&#13;&#10;&#13;&#10;";

                                                        fileSystemOutliers++;

                                                        if(fileSystemOutliers==10)
                                                        {
                                                                        outliers="Alert: recieved more than 10 outliers, so displaying only the top ten results&#13;&#10;"+outliers;
                                                                        break;
                                                        }
                                                }

                        document.getElementById("outlierArea").innerHTML = outliers;

                                                if(fileSystemOutliers == 0)
                                                {
                                                        document.getElementById("outlierArea").innerHTML = "No Outliers found!!!";
                                                }



});



g2 = new Dygraph(

    document.getElementById("graphdiv2"),

<?php

##      $dbhost = '****';

##   $dbuser = '****';

##   $dbpass = '****';

##   $dbname = '****';

##

##$con=mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

##// Check connection
##if (mysqli_connect_errno())

##  {

##  echo "Failed to connect to MySQL: " . mysqli_connect_error();

##  }

$sql='SELECT DISTINCT CONCAT(CONCAT(FileSystem,\'-\'),node) FROM storage_lustre_node_load  where (utime <= UNIX_TIMESTAMP( NOW()) AND utime >= UNIX_TIMESTAMP( DATE_ADD(NOW(), INTERVAL -1 DAY)))  ORDER BY FileSystem, node';

$data=array();

$headers = array();

$headers[0] ="time";

#echo ("time")

if ($result=mysqli_query($db,$sql))

  {

  // Fetch one and one row

  while ($row=mysqli_fetch_row($result))

    {

        $headers[] = $row[0];

        #echo " , ".$row[0];

    }

  // Free result set

  mysqli_free_result($result);

}

$data[] = $headers;

#print_r($headers);



$sql='SELECT DISTINCT utime, CONCAT(CONCAT(FileSystem,\'-\'),node),load_1m FROM storage_lustre_node_load  where (utime <= UNIX_TIMESTAMP( NOW()) AND utime >= UNIX_TIMESTAMP( DATE_ADD(NOW(), INTERVAL -1 DAY)))   ORDER BY utime';

if ($result=mysqli_query($db,$sql))

  {

  // Fetch one and one row

  $rowData=array();
 $count = count($headers);

  $rowData = array_fill(0, $count, '0');

  while ($row=mysqli_fetch_row($result))

    {

        if($rowData[0] != $row[0])

        {

                $data[] = $rowData;

                $rowData=array();

                $count = count($headers);

                $rowData = array_fill(0, $count, '0');
                #$rowData[0] = date('Y/m/d H:i', $row[0]);

                $rowData[0] = $row[0];# gmdate("\"Y-m-d H:i:s\"", $row[0]);

        }

        $index = array_search($row[1], $headers);

        #echo "\n".$row[1]." found at ".$index;

        $rowData[$index] = $row[2];

    }

  // Free result set

  mysqli_free_result($result);

}



#print_r($data);

#echo('<table border=2>');

for($i=0;$i<(count($data)-1);$i++) {

  if($i==1)

   continue;

  if($i==0)

        echo ("\"".$data[$i][0] );

  else

    echo date("\"Y-m-d H:i:s",$data[$i][0]);
 for($j=1;$j<count($data[$i]);$j++) {

    echo(' , ' . $data[$i][$j] );

  }

    echo ("\\n\" +" );

}

for($i=(count($data)-1);$i<(count($data));$i++) {

    echo date("\"Y-m-d H:i:s",$data[$i][0]);

  for($j=1;$j<count($data[$i]);$j++) {

    echo(' , ' . $data[$i][$j] );

  }

    echo ("\\n\"" );

}

##mysqli_close($con);

mysqli_close($db);
?>,

    {

                                title: 'Node vs. Load Time',

                                legend: 'follow',

                                ylabel: 'Load per minute',

                            xlabel: 'Time stamp',

        strokeWidth: 1,

        strokeBorderWidth: 1,

                highlightSeriesOpts: {

          strokeWidth: 1,

          strokeBorderWidth: 1,

          highlightCircleSize: 2,

        }

        }       // options

  );

</script>

<footer>
 <p> <center> Storage Enabling Technologies Team , NCSA, UIUC
</br>
</br>
Contact information: <a href="mailto:set@ncsa.illinois.edu">set@ncsa.illinois.edu</a> </center> </p>
</footer>

</body>

</html>
