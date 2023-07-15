<div class="signup-form">
    <form  method="POST" enctype="multipart/form-data" >
		<h2>Hotel Data</h2>
		
<label>Name: </label>         
<input type="text" name="name" class="form-control"> <br> <br>

<label>Hotel: </label>
<select name="type" required>
    <option value=""> Type </option>
    <option value="sell"> Sell </option>
	<option value="rent"> Rent </option>
</select>  
  
<br>  
<br>  
     
<label>Facility:</label>
<br>  
<input type="checkbox" id="" name="facility[]" value="Playground">
<label for="facility1"> Playground</label><br>
<input type="checkbox" id="" name="facility[]" value="Parking">
<label for="facility2"> Parking</label><br>
<input type="checkbox" id="" name="facility[]" value="Swimmingpool">
<label for="facility3"> Swimmingpool</label><br><br>

<label>
Description  
<br>  
<textarea cols="40" rows="5" value="description"name="description" class="form-control">  
</textarea> 
<br>
<br> 

<label> 
Status :  
</label><br>  
<input type="radio" name="status" value="1" checked/> Yes <br>  
<input type="radio" name="status" value="0"/> No <br>  
<br><br> 

        
    <div class="form-group">
        <input type="file" class="form-control" required="true" name="image[]" multiple >
    </div>      
      
	<div class="form-group">
        <button type="submit" class="btn btn-success btn-lg btn-block" name="submit">Submit</button>
    </div>
    </form>