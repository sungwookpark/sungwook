<?php session_start();?>
<?php require_once("includes/db_connection.php")?>
<?php require_once("includes/functions.php");?>
<?php $myID = $_SESSION['username'];?>


<?php 
//confirm logged in
if(!isset($_SESSION['admin_id'])){
    redirect_to("index.php");
}
?>



<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/jquery.mobile.flatui.css" />
  <link rel="stylesheet" type="text/css" href="css/mycss.css" />
  <script src="js/jquery.js"></script>
  <script src="js/jquery.cookie.js"></script>
  <script src="js/jquery.mobile-1.4.0.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3&sensor=false&language=ko"> </script>
  <script src="js/myscript.js"></script>

    
</head>
    
<body>
    
    <!--header external fixed toolbar-->
    <header data-role="header" data-position="fixed" data-theme="b">
       <a data-iconpos="notext" href="#mypanel" data-role="button" data-icon="flat-settings" title="settings"></a>
        
      <h1 class="ui-title" >다모여</h1>
      <div class="ui-btn-right" data-role="controlgroup" data-type="horizontal">


      <a href="#findpage" data-role="button" data-icon="flat-location" data-theme="b" data-inline="true" id="btn_find">팟찾기</a>
      <a href="#mypage" data-role="button" data-icon="flat-man" data-theme="b" data-inline="true" id="btn_my">참여팟</a>
      <a href="#madenpage" data-role="button" data-icon="star" data-theme="b" data-inline="true" id="btn_made">개설팟</a>
      <a href="logout.php" rel="external" data-role="button" data-icon="flat-cross" data-theme="b" data-inline="true">나가기</a>
       </div>   
    </header> <!--header end-->

 

<!--find party page (findpage)-->
  <div id="findpage" data-role="page" data-title="모임찾기">
    <div data-role="content" role="main" id="list"> 
        <?php 
            if(isset($_SESSION['distance'])){
                //거리 설정을 했으면
                echo partylist('distance');
            }else{
                echo partylist('find');
            }

        ?>
      </div>
      
      <!--footer-->
      <div style="background-color: rgba(0,0,0,.0); border:0;"id="foo" data-role="footer" data-position="fixed">        
         <a data-mini="true"href="#registerpage" data-role="button" data-theme="d" data-icon="flat-plus" style="float:right; margin-right:5px;border-radius:32px;">팟 등록</a>
      </div>
      
  </div><!--find page end-->
        

    
    
<!--my party page (mypage)-->
  <div id="mypage" data-role="page" data-title="참여모임">
    <div data-role="content" role="main" id="list2"> 
            <?php echo partylist('my');?>
      </div>
  </div><!--mypage end-->    
    
    
    
<!--maden by me party page (madenpage)-->
  <div id="madenpage" data-role="page" data-title="개설모임">
    <div data-role="content" role="main" id="list3"> 
            <?php echo partylist('maden');?>
      </div>
  </div>
      
  <!--madenpage end-->    
    
    
    
    
    
<!--party page-->
 <div id="partypage" data-role="page" >
     
    <div id="navibar"></div><!-- /navbar -->

     
     <div data-role="content" role="main" id="partypageContent">
         
         <div id="partyPageName"></div>
         <hr>
         <div id="partyPageExp"></div>
         <hr>
         <div id="partyPageDate"></div>
         <hr>
         <div id="partyPageLoc"></div>
         <div id="partyMap" style="width:100%;height: 400px;margin: auto"> </div>

    </div>
        <!--footer-->
     <div id="partyfooter"></div>
</div><!--party page end-->   
    
<!--board page-->
 <div id="boardpage" data-role="page" >
     
     <div id="navibar2"></div><!-- /navbar -->
     
     <div data-role="content" role="main">
          <ul data-role="listview" data-inset="true" data-filter="true" data-filter-theme="b" data-filter-placeholder="게시글을 검색하세요" data-divider-theme="d">
      <li  data-theme="a" data-role="list-divider">
            게시판

      </li>
              
        <?php 
        $query = "SELECT * ";
        $query .= "FROM boards ";
        $query .= "WHERE party_id = {$_COOKIE['partyid']} ";
        $query .= "ORDER BY id DESC";
        $board_set = mysqli_query($connection,$query);
        //Test if there was a query error

        if(!$board_set){
            die("db query failed.");
        }

              ?>
        <?php 
            //3.Use returned data (if any)
            while($board = mysqli_fetch_assoc($board_set)){
                //output data from each row
        ?>
                <li data-theme="c"><a href="#partypage">
                    <p class="ui-li-aside partyDate"> <?php echo $board["time"] ?></p>       
                    <div id="listInfo">
                    <h5 ><?php echo $board["title"] ?> <span class="ui-li-count">
                        <?php echo $board["writer"] ?></span></h5>
                    <p>  <?php echo $board["content"] ?></p>                 
                    </div>
                    </a>
                </li>     
                    
        <?php 
            } 
        ?>
    </ul>  
    </div><!--content end-->
     
 <!--footer-->
      <div style="background-color: rgba(0,0,0,.0); border:0;"id="board_foo" data-role="footer" data-position="fixed">

                  
         <a data-mini="true"href="#" data-role="button" data-theme="f" data-icon="flat-plus" data-iconpos="notext" style="float:right; margin-right:5px;border-radius:32px;"></a>
      </div>
</div><!--board page end-->   

   
     <!--memberpage-->
  <div id="memberpage" data-role="page" data-title="멤버">
    <div id="navibar4"></div><!-- /navbar -->
    <div data-role="content" role="main" id="list4"> 
        <div id="memlist"></div>
      </div>
  </div><!--memberpage end-->     

  
    
  <!--register page-->    
 <div id="registerpage" data-role="page" >
     <div data-role="content" role="main">
        <form method="post" action="create_party.php" data-ajax="false">

            
         <!--interest category-->
             <select name="inter" id="interest2" data-native-menu="false" data-theme="f" >
                 <option>관심분야 선택</option>
                 <optgroup label="<<<< 야  외  활  동 >>>>" >
                  
                    <option value="등산">등산</option>
                    <option value="레저/스포츠">레저/스포츠</option>
                    <option value="사진">사진</option> 
                    <option value="자전거">자전거</option>
                    <option value="스키/보드">스키/보드</option>  
                    <option value="여행/캠핑">여행/캠핑</option>  
                    <option value="차/오토바이">차/오토바이</option>  
                 </optgroup>
                 
                <optgroup label="<<<< 실  내  활  동 >>>>">
                    <option value="스터디/자기계발">스터디/자기계발</option>
                    <option value="영화/공연">영화/공연</option>
                    <option value="책/독서">책/독서</option>
                    <option value="음악/악기">음악/악기</option>                           
                    <option value="전시">전시</option></option>
                 </optgroup>
    
                <optgroup label="<<<< 기    타 >>>>">
                    <option value="봉사활동">봉사활동</option>
                    <option value="사교/인맥">사교/인맥</option>
                    <option value="패션">패션</option>                
                    <option value="여성">여성</option></option>                
                    <option value="자유주제">자유주제</option></option>
                 </optgroup>
             </select>
         

        <!--location-->
         <label for="location" class="ui-hidden-accessible">location explain</label>
         <input type="text" name="location" id="location" value="" placeholder="위치 설명 ex)아주대 삼거리 롯데리아" />
        <!--google map-->
        <a href="#mapdialog" data-role="button" data-theme="e" id="address">위치 선택 (지도)</a>
        <input type="hidden" name="saveloc" id="saveloc" value=""/>  

        <!--title-->
        <label for="partytitle" class="ui-hidden-accessible">partytitle</label>
         <input type="text" name="partytitle" id="partytitle" value="" placeholder="모임 이름을 적어주세요"/>
        <!--detail-->
        <label for="details" class="ui-hidden-accessible">details</label>
        <textarea id="details" name="partydetail" placeholder="어떤 모임인지 설명해주세요"></textarea> 
        <!--date-->
         <label for="partydate" class="ui-hidden-accessible">birthdate</label>
         <input type="date" name="partydate" id="partydate" value="" placeholder="모임 날짜를 입력해주세요"/> 
        <!--# of member-->
        <label for="slider" >정원(~100명)</label>
        <input type="range" id="slider" name="slider" value="50" min="2" max="100" data-highlight="true" data-theme="f" />



        <!--summit button-->
        <label for="party_submit" class="ui-hidden-accessible">submit</label>
         <input type="submit" onclick ="toast('모임이 생성되었습니다')" name="submit" id="submit" value="모임 생성" data-theme="d" />


        </form>
        
     </div><!--content end-->
  </div><!--register page end-->
  

  <!--map dialog-->
 <div data-theme="e" id="mapdialog" data-role="page" data-dialog="true">
        <div data-role="header">
           <h1></h1>
            <a href="#registerpage" data-icon="arrow-l" data-rel="back" data-role="button" >위치 저장</a> 
           
        </div>
        <div data-role="content">
            <div id="myMap" style="width:100%;height: 400px;margin: auto"> </div>
            <div id="markLocation"></div>
</div><!--map dialog end-->
    
     
 
    
    
<!--widget page-->
  <div id="widget" data-role="page">

    <div data-role="content">
      <p>Sample text and <a href="#">links</a></p>
      <fieldset class="ui-grid-a">
        <div class="ui-block-a"><button data-icon="flat-settings" data-theme="a">Button A</button></div>
        <div class="ui-block-b"><button data-icon="flat-new" data-theme="b">Button B</button></div>
      </fieldset>
      <fieldset class="ui-grid-a">
        <div class="ui-block-a"><button data-icon="flat-man" data-theme="c">Button C</button></div>
        <div class="ui-block-b"><button data-icon="flat-mail" data-theme="d">Button D</button></div>
      </fieldset>
      <fieldset class="ui-grid-a">
        <div class="ui-block-a"><button data-icon="flat-lock" data-theme="e">Button E</button></div>
        <div class="ui-block-b"><button data-icon="flat-menu" data-theme="f">Button F</button></div>
      </fieldset>
      <fieldset class="ui-grid-a">
        <div class="ui-block-a"><button data-icon="flat-heart" data-theme="g">Button G</button></div>
      </fieldset>

      <ul data-role="listview" data-inset="true">
        <li data-role="list-divider" data-theme="a">List Header</li>
        <li>Read-only list item</li>
        <li><a href="#">Linked list item</a></li>
      </ul>

      <div data-role="collapsible-set" data-theme="b" data-content-theme="b">
        <div data-role="collapsible" data-collapsed-icon="flat-time" data-expanded-icon="flat-cross" data-collapsed="false">
          <h3>Section 1</h3>
          <p>I'm the collapsible content for section 1</p>
        </div>
        <div data-role="collapsible" data-collapsed-icon="flat-calendar" data-expanded-icon="flat-cross">
          <h3>Section 2</h3>
          <p>I'm the collapsible content for section 2</p>
        </div>
        <div data-role="collapsible" data-collapsed-icon="flat-settings" data-expanded-icon="flat-cross">
          <h3>Section 3</h3>
          <p>I'm the collapsible content for section 3</p>
        </div>
      </div>

      <div data-role="fieldcontain">
        <fieldset data-role="controlgroup">
          <input type="radio" name="radio-choice-a" data-theme="c" id="radio-choice-1-a" value="choice-1" checked="checked" />
          <label for="radio-choice-1-a">Radio 1</label>

          <input type="radio" name="radio-choice-a" data-theme="c" id="radio-choice-1-b" value="choice-2"  />
          <label for="radio-choice-1-b">Radio 2</label>
          <input type="checkbox" name="checkbox-a" data-theme="c" id="checkbox-a" checked="checked" />
          <label for="checkbox-a">Checkbox</label>
        </fieldset>
      </div>

      <div data-role="fieldcontain">
        <a href="#" data-role="button" data-icon="home" data-iconpos="notext" data-theme="b" data-inline="true">Home</a>
        <a href="#" data-role="button" data-icon="flat-video" data-iconpos="notext" data-theme="b" data-inline="true">Video</a>
        <a href="#" data-role="button" data-icon="flat-time" data-iconpos="notext" data-theme="b" data-inline="true">Time</a>
        <a href="#" data-role="button" data-icon="flat-settings" data-iconpos="notext" data-theme="b" data-inline="true">Settings</a>
        <a href="#" data-role="button" data-icon="flat-plus" data-iconpos="notext" data-theme="b" data-inline="true">Plus</a>
        <a href="#" data-role="button" data-icon="flat-new" data-iconpos="notext" data-theme="b" data-inline="true">New</a>
        <a href="#" data-role="button" data-icon="flat-menu" data-iconpos="notext" data-theme="b" data-inline="true">Menu</a>
        <a href="#" data-role="button" data-icon="flat-man" data-iconpos="notext" data-theme="b" data-inline="true">Man</a>
        <a href="#" data-role="button" data-icon="flat-mail" data-iconpos="notext" data-theme="b" data-inline="true">Mail</a>
        <a href="#" data-role="button" data-icon="flat-lock" data-iconpos="notext" data-theme="b" data-inline="true">Lock</a>
        <a href="#" data-role="button" data-icon="flat-location" data-iconpos="notext" data-theme="b" data-inline="true">Location</a>
        <a href="#" data-role="button" data-icon="flat-heart" data-iconpos="notext" data-theme="b" data-inline="true">Heart</a>
        <a href="#" data-role="button" data-icon="flat-eye" data-iconpos="notext" data-theme="b" data-inline="true">Eye</a>
        <a href="#" data-role="button" data-icon="flat-cross" data-iconpos="notext" data-theme="b" data-inline="true">Cross</a>
        <a href="#" data-role="button" data-icon="flat-cmd" data-iconpos="notext" data-theme="b" data-inline="true">Cmd</a>
        <a href="#" data-role="button" data-icon="flat-checkround" data-iconpos="notext" data-theme="b" data-inline="true">Checkround</a>
        <a href="#" data-role="button" data-icon="flat-calendar" data-iconpos="notext" data-theme="b" data-inline="true">Calendar</a>
        <a href="#" data-role="button" data-icon="flat-bubble" data-iconpos="notext" data-theme="b" data-inline="true">Bubble</a>
        <a href="#" data-role="button" data-icon="flat-volume" data-iconpos="notext" data-theme="b" data-inline="true">Volume</a>
        <a href="#" data-role="button" data-icon="flat-camera" data-iconpos="notext" data-theme="b" data-inline="true">Camera</a>
      </div>

      <select name="flip-1" id="flip-1" data-role="slider">
        <option value="off">Off</option>
        <option value="on" selected>On</option>
      </select>

      <div data-role="fieldcontain">
        <div data-role="controlgroup" data-type="horizontal">
          <a href="#" data-icon="flat-mail" data-theme="a" data-iconpos="notext" data-role="button">Yes</a>
          <a href="#" data-icon="flat-camera" data-theme="a" data-iconpos="notext" data-role="button">Yes</a>
          <a href="#" data-icon="flat-heart" data-theme="a" data-iconpos="notext" data-role="button">Yes</a>
          <a href="#" data-icon="flat-eye" data-theme="a" data-iconpos="notext" data-role="button">Yes</a>
        </div>
      </div>

      <div data-role="fieldcontain">
        <select name="select-choice" id="select-choice-a" data-native-menu="false" data-theme="a">
          <option value="standard">Option 1</option>
          <option value="rush">Option 2</option>
          <option value="express">Option 3</option>
          <option value="overnight">Option 4</option>
        </select>
      </div>

      <input type="text" placeholder="Text Input" />
      <div data-role="fieldcontain">
        <input type="range" name="slider" value="50" min="0" max="100" data-highlight="true" />
      </div>
        
        
        
        
      <form method="get" action="">
        
         <label for="basicfiled" class="ui-hidden-accessible">Basic Text Field</label>
         <input type="text" name="basicfield" id="basicfield" value="" placeholder="input text"/>
         <label for="age" class="ui-hidden-accessible">age</label>
         <input type="number" name="age" id="age" value="" placeholder="input age"/>          
         <label for="email" class="ui-hidden-accessible">email</label>
         <input type="email" name="email" id="email" value="" placeholder="input email"/>  
             
         <label for="birthdate" class="ui-hidden-accessible">birthdate</label>
         <input type="date" name="birthdate" id="email" value="" placeholder="input birthdate"/>    
           
        <label for="search" class="ui-hidden-accessible">search</label>
         <input type="search" name="search" id="search" value="" placeholder="Search"/>         
             
        <label for="details" class="ui-hidden-accessible">details</label>
        <textarea id="details" placeholder="tell me more"></textarea>  
        
            <!--radio button-->
           <fieldset data-role = "controlgroup">
              <legend>Select an option:</legend>   
              <label for="rb1" >rb1</label>
              <input type="radio" name="rb" id="rb1" value="rb1" data-theme="e"/>  
              <label for="rb2"  >rb2</label>
              <input type="radio" name="rb" id="rb2" value="rb2" checked="checked" data-theme="e"/>  
               <label for="rb3" >rb3</label>
              <input type="radio" name="rb" id="rb3" value="rb3" data-theme="e"/>  
            </fieldset>
             
              <!--radio button horizontal-->
            <fieldset data-role = "controlgroup" data-type="horizontal">
              <legend>Select an option:</legend>   
              <label for="rb4">남자</label>
              <input type="radio" name="rb2" id="rb4" value="rb4" data-theme="b"/>  
              <label for="rb5"  >여자</label>
              <input type="radio" name="rb2" id="rb5" value="rb5"  data-theme="b"/>  
            </fieldset>
             
             <!--check box-->
             <fieldset data-role = "controlgroup" >
            <legend>Choose all</legend> 
             <label for="checkbox-a">aaa</label>
             <input type="checkbox" name="checkbox-a" data-theme="c" id="checkbox-a" />
              <label for="checkbox-b">bbb</label>
             <input type="checkbox" name="checkbox-b" data-theme="c" id="checkbox-b" />
             <label for="checkbox-c">ccc</label>
             <input type="checkbox" name="checkbox-c" data-theme="c" id="checkbox-c"  />
             </fieldset>
             
             <!--slide bar-->
             <legend>Numerical Slider Example</legend> 
             <label for="num"  class="ui-hidden-accessible">Enter a number</label>
             <input type="range" name="num" id="num" value="25" min="0" max="100"
                    data-highlight="true" />
             
             <!--flip switch-->
              <select name="flip-1" id="flip-1" data-role="slider" data-theme="f">
                <option value="off" >Off</option>
                <option value="on" selected>On</option>
              </select>
             
             <!--select list-->
            <div data-role="fieldcontain">
             <select name="select-choice" id="select-choice-a" data-native-menu="false" data-theme="d">
                  <option value="standard">Option 1</option>
                  <option value="rush">Option 2</option>
                  <option value="express">Option 3</option>
                  <option value="overnight">Option 4</option>
            </select>
             </div>

             <!--select cumstom list-->
             <select name="select2" id="select2" data-native-menu="false" data-theme="a">
                <option disabled="disabled">Choose one:</option>
                 <optgroup label="First Group">
                    <option value="value1">Value 1</option>
                    <option value="value2" disabled="disabled">Value 2</option>
                    <option value="value3">Value 3</option>
                    <option value="value4">Value 4</option>                
                 </optgroup>
                <optgroup label="Second Group">
                    <option value="value5">Value 5</option>
                    <option value="value6" disabld="disabled">Value 6</option>
                    <option value="value7">Value 7</option>
                    <option value="value8">Value 8</option>                
                 </optgroup>
             </select>
             
             
             
             <!--multiple select -->
             <filedset class="ui-field-contain">
                <label for="select5"  class="ui-hidden-accessible">Multiple Enabled</label>
            <select data-theme="b" name="select5" id="select5" multiple="multiple" data-native-menu="false">
                    <option>Select options: </option>
                    <option value="value1">Value 1</option>
                    <option value="value2">Value 2</option>
                    <option value="value3">Value 3</option>
                    <option value="value4">Value 4</option>                
                    <option value="value5">Value 5</option>
                    <option value="value6">Value 6</option>
                    <option value="value7">Value 7</option>
                    <option value="value8">Value 8</option>           
                
             </select>
             </filedset>

         </form>
        
    </div><!--content end-->
  </div><!--widget page end-->


<?php 
    // Close databse connection
    mysqli_close($connection);

?>

</body>
</html>
   