<?php 
include '../include/secure_coord.php';
require ("../include/header.php");
	
?>
<div class="panel-heading" align="center">
    View Status
</div>
<div class="panel-body">
	<form role="form" class="form-inline" method="POST">
		<table>
			<tr>
				<div class="form-group">
					<td>
						<label>Select Branch: </label>
					</td>
					<td>
						<select name="branchId" id="branchlist" class="form-control">
							<?php
								$branchlist = $obj->get_branches($conn);
								while($branch = $branchlist->fetch()) {
									$branchId = $branch["branchId"];
									$branchName = $branch["branchName"];
									
									echo "<option value='$branchId'>$branchName</option>";
								}		
							?>
						</select>
					</td>
				</div>
			</tr>
			<tr>
				<div class="form-group">
					<td>
						<label>Select Semester: </label>
					</td>
					<td>
						<select name="semester" class="form-control">
							<?php
								$i = 1;
								while($i<=10) {
									echo "<option value=$i>$i</option>";
									$i ++;
								}	
							?>
						</select>
					</td>
				</div>
			</tr>
			<tr>
				<div class="form-group">
					<td>
						<input type="submit" value="View Faculties" name="getdata" class='btn btn-outline btn-default'>

					</td>
				</div>
			</tr>
		</table>
	</form>
	<div class="panel-inside">
	<!-- part if form submitted -->
	
	<?php
		// if(isset($_POST['appdisapp'])) {
		// 	$facultyId = htmlentities($_POST['facultyId']);
		// 	$subjectId = htmlentities($_POST['subjectId']);
		// 	$approve = htmlentities($_POST['approve']);

		// 	$obj->update_approval($conn, $facultyId, $subjectId, $approve);
			
			
		// }
		if(isset($_POST['getdata'])) {
			$branchId = htmlentities($_POST['branchId']);
			$branchName = $obj->get_branchname($conn, $branchId);
			$semester = htmlentities($_POST['semester']);
			//select college id for the particular College Representative
			$collegeId = $id;
			
			//select faculty list with subject for the specific college
			$faculties = $obj->get_branch_faculties($conn, $collegeId, $branchId);
			
			echo "<div class='table-responsive'><table class='table table-striped table-bordered table-hover' id='subtable'>";
			echo "<thead><tr>
			<th>Faculty<br>Id</th>
			<th>Faculty<br>Name</th>
			<th>Subject<br>Id</th>
			<th>Subject<br>Name</th>
			<th>Teaching Experience<br><em>(Years)</em></th>
			<th>View<br>Faculty</th>
			<th>Status</th>
			<th>Delete</th>
			</tr>
			</thead>";
			$count = 0;
			$i = 0;
			while($row = $faculties->fetch()) {
				$facultyid = $row['id'];
				$facultyname = $row['name'];
				$subjectlist = $obj->get_subject_list_faculty_all($conn, $facultyid, $semester);
				$teach_exp = $row['teach_exp'];
				while($sub = $subjectlist->fetch()) {
					$subjectId = $sub['subjectId'];
					$subjectName = $sub['subjectName'];
					$status = $obj->get_approval_status($conn, $facultyid, $subjectId);
					$class = ($count%2)?'even':'odd'; 
					$i = $i+1;
					echo "<tr class='$class'>
							<td>$facultyid</td><td>$facultyname</td><td>$subjectId</td><td>$subjectName</td><td>$teach_exp</td>";

					echo "<td><a href='view_faculty.php?facultyid=$facultyid'>View</a></td>";
					if($status == 0) 
						echo "<td>No Action Takern</td>";
					else if($status == 1) 
						echo "<td>Approved</td>";
					else if($status == 2)
						echo "<td>Not Approved</td>";
					echo "<td><div id='delete".$i."'<button class='btn btn-danger' onclick='deleteme(\"".$facultyid."\",\"".$subjectId."\",\"".$i."\");'>Delete</button></div></td>";
					echo "</tr>";
				}
			}
			echo "</table></div>";
		}
	?>
   </div>
</div>        
<?php
	require ("../include/footer.php");
?>