<?php $base=base_url();

echo "<?php xml version=\"1.0\"?>\n";
echo "<cu3er>
	<settings>
		<auto_play>
			<defaults symbol='circular'/>
			<tweenIn x='450' y='25' width='30' height='30' tint='0xFFFFFF' alpha='0.5'/>
			<tweenOver alpha='1'/>
		</auto_play>
	</settings>
	<slides>
    <slide>
  		<url>$base/assets/flash/images/slide3.jpg</url>
    </slide>
		<transition direction='left' shader='phong' delay='0.2'/>
			<slide>
        <url>$base/assets/flash/images/slide2.jpg</url>
      </slide>
		<transition num='4' direction='down' shader='flat' />
			<slide>
      	<url>$base/assets/flash/images/slide4.jpg</url>
      </slide>
		<transition num='4' slicing='vertical' direction='left'  delay='0.2'  />
      <slide>
      	<url>$base/assets/flash/images/slide5.jpg</url>
      </slide>
		<transition num='4' shader='phong' delay='0.2'/>
	</slides>
  <slide>
    <url>$base/assets/flash/images/slide1.jpg</url>
  </slide>
  <transition num='4' slicing='vertical' direction='down'/>
</cu3er>";
?>
