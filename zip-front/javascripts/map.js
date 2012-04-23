$(function(){

  // # center

  // center content on page

  $(window).resize(function(){

    var $content = $('.content');

    $content.css({
      left: ( $(window).width() - $content.width() ) / 2,
      top: ( $(window).height() - $content.height() ) / 2
    });

    // center share buttons horizontally [nuke]

   // $('.share').css('left', ( $(window).width() - $('.share').width() ) / 2);

    // center photo nav vertically [nuke]

    $('.nav').css('top', ( $(window).height() - $('.nav').width() ) / 2 );

    // center photo data vertically [nuke]

    $('.data').css('top', ($content.height() - $('.data').height()) / 2 );

  });

  $(window).resize();

  // # polymap

  // create polymap

  var po = org.polymaps;

  // append map svg to page

  var svg = $(po.svg("svg")).appendTo('#map').get(0);

  // set map variables

  var map = po.map().container(svg).center({lat: 46, lon: -52}).zoom(3.5).add(po.interact());
  
  // set zoom range
  
  map.zoomRange([2,10]);

  // add tiles to the map, colors: 998, 29637, 26490

 // map.add( po.image().url(po.url("http://{S}tile.cloudmade.com/ad5ebee15579481983cb9318baea0d0f/26490/256/{Z}/{X}/{Y}.png").hosts(["a.", "b.", "c.", ""]) ));

  //map.add( po.image().url(po.url("http://{S}tile.cloudmade.com/1a1b06b230af4efdbb989ea99e9841af/26490/256/{Z}/{X}/{Y}.png").hosts(["a.", "b.", "c.", ""]) ));

  map.add ( po.image().url(po.url("http://{S}tile.cloudmade.com/cfc96afd35bc4c12b3f06893fff79e8c/60666/256/{Z}/{X}/{Y}.png").hosts(["a.", "b.", "c.", ""]) ));
  

  // geojson the photos onto map

  map.add( po.geoJson().url("photos.json").on("load", load).tile(false) /*.zoom(2)*/ );

  // load function

  function load(e) {

    // empty the photos list

    // $('.photos').empty();

    // get the tile element and empty it

    var g = e.tile.element;

    $(g).empty();

    // loop through each feature and add circle to map

    for (var i = 0; i < e.features.length; i++) {

      // declare photo, coordinates, and point variables
	  // IMPORTANT: this code pulls the location data from the photos
      var photo = e.features[i].data.properties,
          coordinates = e.features[i].data.geometry.coordinates,
          point = $( po.svg("circle") ).appendTo(g).get(0);

      // set the position and sizing parameters

      point.setAttribute("cx", coordinates.x);
      point.setAttribute("cy", coordinates.y);

      // dynamically set fill and photo_id

      if (photo.instagram_id != null) {

        point.setAttribute("r", 6); //size of dot

        $(point).attr({
          fill: "#fecb00",
          photo_id: photo.id
        });

      } else {

        point.setAttribute("r", 5);

        $(point).attr({
          fill: "white",
          photo_id: photo.id
        });

      }

      //$(point).addClass("point");

      // add a photo list item to photos list

      if ( $('.photos li').hasClass("photo_" + photo.id) == false ) {

        $('<li class="photo_' + photo.id + '">' + photo.title + '</li>').data('photo', photo).appendTo('.photos');

      }

    }

    // # GUI Actions

    $('circle').bind('click', function(){

      //console.log('clicked');

      $( '.photo_' + $(this).attr("photo_id") ).click();

    });

    // Click Photo item

    $('.photos li').unbind('click').bind('click', function(){

      //console.log('show');

      // Declare variables

      var $photo = $(this),
          data = $photo.data('photo'),
          $content = $('.content'),
          point = { lat: data.latitude, lon: data.longitude };
          user = data.user;

      // Center map and zoom in

      //map.zoom(10);

      map.center(point);

      // Set photo to active

      $photo.addClass('active').siblings('li').removeClass('active');

      // Change content data

      $('.photo').fadeOut('slow', function(){

        $('.photo img.pcp').attr('src', data.image);
        
        $('.photo img.slug').attr('src', data.profile_picture);

        $('.content h3').text(data.title);

        $('.content .description').text(data.description);

        $('.content .user').text(user);

        //$('.content .user').text(data.user);

        $('.data').css('top', ($('.content').height() - $('.data').height()) / 2 );

      })

      // Find appropriate height, depends on photo, then apply to content div

      $content.css('height', 300);

      // Fade in content & nav

      $('.nav, .content, .photo').fadeIn('slow', function(){

        //$('.content img').attr('src', data.image);

        //$('.content').css('background-image', 'url(' + data.image + ')');

        // Center the content div on the page

        $(window).resize();

      });

      // Fade out map

       $('svg > .layer').animate({opacity: 0.8}, 1000);

    });

    // Show Interaction dialogue

    $('.interact a').click( function(){

      $('.content h3').text('instagram');

      $('.content .description').html("");//shown on clicking  "instructions" link

      $('.content .user').text('');

      $('.content').fadeIn( function(){

        $(window).resize();

      });

    });

    // Close Content

    $('.map').click( function(e){

      console.log(e);

    });

    $('.close').click( function(){ closeContent(); });

    // Continue from Intro

    $('.continue').click( function(){

      $('.tech').fadeOut( function(){ $('.continue').hide(); $('.close').show(); } );

      closeContent();

    });

    // Prev / Next Photo

    $('.prev-photo').unbind('click').bind('click', function(){ nextPhoto(); });

    $('.next-photo').unbind('click').bind('click', function(){ prevPhoto(); });

    // # Functions

    var prevPhoto = function(){

      if ( $('.photos li.active').prev().click().length == 0 ) {
        $('.photos li:last').click();
      }

    }

    var nextPhoto = function(){

      if ( $('.photos li.active').next().click().length == 0 ) {
        $('.photos li:first').click();
      }

    }

    var closeContent = function(){

      $('.content, .nav').fadeOut();

      $('svg > .layer').animate({opacity: 1}, 1000);

    }

    // # Keyboard

    $(document).keyup(function(e) {

      switch(e.keyCode){

        case 27: // esc
        closeContent();
        break;

      }

    });

  }

});