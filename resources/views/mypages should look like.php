$records=  Models\AcademicRecordsModel::where("stuId",$sql)->groupBy("year")->groupBy("class")->orderBy("class")->get();
                                

	  ?>

      
        <table width='800px' style="text-align:left; ;font-size: 16px" height="90" class=""  border="0" style="margin-left:32px">
        <tr>
        
          <td  style=" " align="left"> 
            <?php  
               
            foreach ($records as $row){
            for($i=1;$i<3;$i++){
             $query=  Models\AcademicRecordsModel::where("stuId",$sql)->where("year",$row->year)->where("term",$i)->get()->toArray();
                
              
                if(count($query)>0){

                echo "<div class='uk-text-bold' align='left' style='margin-left:33px'>Year : ".$row->year."    ";
                echo ", Term : ".$i;
                 echo ", Class :  " .$row->class." <hr/></div>";
                 

                  
                 
              
                
         ?>
         
            <div class="uk-overflow-container">
           <table style="margin-left:32px"  border="0" style=""width='940px'  class="uk-table uk-table-striped ">
                <thead >
                    <tr class="uk-text-bold" style="background-color:#EAF2D3;color:white;">
                     <th>SUBJECT</th>

                    <th style=';' class='ui-corner-top'>MARKS SCORED</th>
                    <th style='' class='ui-corner-top'>POSITION</th>
                    <th style='text-align: center' style='width:15%;' class='ui-corner-top'>GRADE</th>
                    <th style='text-align:left;padding-left:1%;' class='ui-corner-top'>REMARKS</th>

                    </tr>
                </thead>
                <tbody>
                  <?php 
		  $classSize=count($query);
                  $ttotal=0.00;
                   $aggregade=0.00;
                foreach ($query as $rs){

                 

                ?>
                  <tr>
                   <?php $object=$sys->getCourseByCodeObject($rs['courseId']);  ?> 
                    <td> <?php 
			 echo strtoupper(@$object[0]->name);?> </td>	
                     
                    <td><?php echo $rs['total']?></td>
                    <td><?php echo $rs['posInSubject'];?></td>
                    <?php
                    
                        $programme=$sys->getCourseProgrammeMounted($rs['courseId']);
                            
                            $program=$sys->getProgramArray($programme);
                              $gradeSys=$sys->getProgramByGradeSystem($programme);
                              
                            $gradeArray = $sys->getGrade($rs['total'], $gradeSys);
                           
                              $grade = $gradeArray[0]->grade;
                              $gradePoint=@$gradeArray[0]->value;
                               $comment=@$gradeArray[0]->comment;
                    
                    ?>
                    <td style='text-align: center'><?php echo $grade;$ttotal+=$rs['total']; ?></td>
                    <td> 
                      <?php
                      
                     			  echo  strtoupper($comment);
				   
				 
				  $aggregade+=$gradePoint;
				  ?>
                    </td>
                     
	  
                    <?php 
                     } ?>
                  </tr>
                  <tr>
                     
                       
                      <td colspan="1"><div align="right" class="uk-text-bold">Total Score: </div></td>
                    <td><span class="uk-text-bold"><?php echo $ttotal; ?></span></td>
                    <td colspan="2"  ><div align="left" class="uk-text-bold">Average Score: <?php echo $ttotal/$classSize; ?></div>                      </td>
                    <td><span class="uk-text-bold">Aggregate: <?php echo $aggregade; ?></span></td>
                </tr>
                         
                </tbody>
                
                 
                    </table> 
        <?php }else{
            echo "<p class='uk-text-danger'>No results to display</p>";
        ?><?php }?>
                <p>&nbsp;</p>     
             </div><?php }  }
    
    ?> 
        
        
        </tr>
  
      </table> 