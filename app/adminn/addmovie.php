<?php 
include 'header.php';
include 'ft.php';
include 'db.php';
?>

<style>
  /* Modal Styles */
.modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%; 
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

</style>





<div class="container">
	<div class="jumbotron">
		<form action="valinewpost.php" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <input type="text" name="mv_name" class="form-control" placeholder="Enter Movie Name" required>
  </div>
  <div class="form-group">
   
    <input type="text" name="mv_m_desc" class="form-control" placeholder="Enter meta description" required>
  </div>
    <div class="form-group">
   
    <input type="text" name="mv_m_tag" class="form-control" placeholder="Enter Meta Tags" required>
  </div>
  <div class="form-group">
   
    <input type="text" name="mv_link1" class="form-control" placeholder="Enter Trailer Link" required>
  </div>
  <div class="form-group">
   
    <input type="text" name="runtime" class="form-control" placeholder="Enter Runtime (2h 1m)" >
  </div>

  <div class="form-group">
    <select name="certificate" class="form-control">
        <option value="" disabled selected>Select Certificate</option>
        <option value="U">U</option>
        <option value="UA">UA</option>
        <option value="A">A</option>
    </select>
</div>

<div class="form-group">
    <select name="status" class="form-control">
        <option value="" disabled selected>Select Status</option>
        <option value="Advance Booking">Advance Booking</option>
        <option value="Promoted">Promoted</option>
        <option value="New Release">New Release</option>
        <option value="Discounted">Discounted</option>
        <option value="">None</option> 
    </select>
</div>






  <div class="form-group">
      <input type="date" name="mv_date" class="form-control" placeholder="Enter Date" >
  </div>

  <div class="form-group">
    <input type="date" name="mv_edate" class="form-control" placeholder="Enter end Date" min="<?php echo date('Y-m-d'); ?>">
  </div>


<!-- 
  <div class="form-group">
                <label for="formats">Select Movie Format:</label><br>
                <input type="checkbox" id="2d" name="formats[]" value="2D">
                <label for="2d"> 2D</label><br>
                <input type="checkbox" id="3d" name="formats[]" value="3D">
                <label for="3d"> 3D</label><br>
                <input type="checkbox" id="imax3d" name="formats[]" value="IMAX 3D">
                <label for="imax3d"> IMAX 2D</label><br>
                <input type="checkbox" id="imax2d" name="formats[]" value="IMAX 2D">
                <label for="imax2d"> IMAX 3D</label>
            </div> -->

            <!-- <div class="form-group">
                <input type="text" name="show_timings" class="form-control" placeholder="Enter Show Timings (e.g. 9am, 1pm)">
            </div> -->



            <!-- <div class="form-group">
    <button type="button" class="btn btn-primary" onclick="openShowTimingsPopup()">Add Show Timings</button>
</div>


<div id="showTimingsContainer">
            </div>
 -->







  <div class="form-group">
   
    <input type="text" name="mv_lang" class="form-control" placeholder="Enter Movie Language" >
  </div>
  
  <div class="form-group">
   
    <input type="text" name="mv_cast" class="form-control" placeholder="Enter Movie Cast" >
  </div>

  <div class="form-group">
   
    <input type="text" name="mv_director" class="form-control" placeholder="Enter Movie Director" >
  </div>
  <div class="form-group">
   
    <input type="text" name="cat_id" class="form-control" placeholder="Enter Category ID" >
  </div>
  <div class="form-group">
   
    <input type="text" name="genre_id" class="form-control" placeholder="Enter Genre ID" >
  </div>
   <div class="custom-file">
    <input type="file" name="img" class="custom-file-input" id="customFile">
    <label class="custom-file-label" for="customFile">Choose file</label>
  </div>
  <br>
  <br>
  <br>
  <div class="form-group">
   <textarea type="text" name="mv_desc" class="form-control" placeholder="Enter Movie description" rows="4"></textarea>
    
  </div>
  <div class="form-group">
        <label for="coming-soon">Coming Soon</label>
        <input type="checkbox" id="coming-soon" name="coming_soon" value="1" checked>
    </div>
<!-- 
    <button type="button" class="btn btn-primary" onclick="addShowTimingsInputs()">Add Show Timings</button>
            <div id="showTimingsContainer"></div>
            <br><br> -->

            <button type="submit" name="submit" class="btn btn-info btn-lg">Submit</button>
        </form>
    </div>
</div>

<!-- <script>
    let showTimingCount = 0;

    function addShowTimingsInputs() {
        showTimingCount++;
        const container = document.getElementById('showTimingsContainer');
        const div = document.createElement('div');
        div.className = 'show-timing';

        div.innerHTML = `
            <div class="form-group">
                <label for="showLang${showTimingCount}">Enter Language (${showTimingCount}):</label>
                <input type="text" id="showLang${showTimingCount}" name="showLang[]" class="form-control" placeholder="Enter Language">
            </div>
            <div class="form-group">
                <label for="showFormat${showTimingCount}">Enter Format:</label>
                <input type="text" id="showFormat${showTimingCount}" name="showFormat[]" class="form-control" placeholder="Enter Format">
            </div>
            <div class="form-group">
                <label for="showTimings${showTimingCount}">Enter Show Timings:</label>
                <input type="text" id="showTimings${showTimingCount}" name="showTimings[]" class="form-control" placeholder="Enter Show Timings">
            </div>
            <div class="form-group">
                <label for="goldPrice${showTimingCount}">Enter Gold Price:</label>
                <input type="text" id="goldPrice${showTimingCount}" name="goldPrice[]" class="form-control" placeholder="Enter Gold Price">
            </div>
            <div class="form-group">
                <label for="platinumPrice${showTimingCount}">Enter Platinum Price:</label>
                <input type="text" id="platinumPrice${showTimingCount}" name="platinumPrice[]" class="form-control" placeholder="Enter Platinum Price">
            </div>
            <hr>
        `;
        container.appendChild(div);
    }
</script>
<script src="path/to/bootstrap.min.js"></script> -->