<div class="row">
	<div class="col-md-12">
		<h1>Laporan Kehadiran</h1>
		<hr/>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<form class="form-horizontal" method="POST" role="form" action="">
			<div class="form-group">
				<label for="presences_date_start" class="col-sm-4 control-label">Date Start</label>
				<div class="col-md-4">
					<input type="text" name="presences_date_start" id="presences_date_start" class="form-control date" data-date-format="DD/MM/YYYY" required="required" placeholder="dd/mm/yyyy" maxlength="10" value="<?php echo $date_start; ?>">
				</div>
				<div class="col-md-4">
					<input type="submit" name="submit" value="Search" class="btn btn-primary">
				</div>
			</div>
			<div class="form-group">
				<label for="presences_date_end" class="col-sm-4 control-label">Date End</label>
				<div class="col-md-4">
					<input type="text" name="presences_date_end" id="presences_date_end" class="form-control date" data-date-format="DD/MM/YYYY" required="required" placeholder="dd/mm/yyyy" maxlength="10" value="<?php echo $date_end; ?>">
				</div>
			</div>
		</form>
	</div>
</div>
<br>
<!-- <form method="POST" action="">
    <label for="presences_date_start">Tanggal Awal</label>
    <input type="date" name="presences_date_start" id="presences_date_start">
    
    <label for="presences_date_end">Tanggal Akhir</label>
    <input type="date" name="presences_date_end" id="presences_date_end">
    
    <button type="submit">Export to Excel</button>
</form> -->
<div class="col-md-6">
        <a href="<?php echo base_url('presences/export_to_excel'); ?>" class="btn btn-success">Export to Excel</a>
</div>
<br><br><br>
<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<!-- <th style="text-align:center;">Tanggal</th> -->
					<!-- <th style="text-align:center;">Hari</th> -->
					<th style="text-align:center;">Nama</th>
					<th style="text-align:center;">Tanggal</th>
					<th style="text-align:center;">Datang</th>
					<th style="text-align:center;">Pulang</th>
					<th style="text-align:center;">Alasan</th>
				</tr>
			</thead>
			<tbody>
			<?php 
				foreach($kehadiran as $row){
					if ($row['alasan'] != 'Masuk') { // Hanya tampilkan jika alasan bukan 'Masuk'
						echo '<tr>';
						echo '<td>'.$row['nama'].'</td>';
						echo '<td>'.$row['tanggal'].'</td>';
						echo '<td>'.$row['datang'].'</td>';
						echo '<td>'.$row['pulang'].'</td>';
						echo '<td>'.$row['alasan'].'</td>';
						echo '</tr>';
					}
				}
			?>
			</tbody>
            
		</table>
	</div>
</div>