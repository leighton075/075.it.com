/*
	Stellar by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
*/

(function ($) {
  var $window = $(window),
    $body = $("body"),
    $main = $("#main");

  // Play initial animations on page load.
  $window.on("load", function () {
    window.setTimeout(function () {
      $body.removeClass("is-preload");
    }, 100);
  });

  // Nav.
  var $nav = $("#nav");

  if ($nav.length > 0) {
    // Shrink effect.
    $main.scrollex({
      mode: "top",
      enter: function () {
        $nav.addClass("alt");
      },
      leave: function () {
        $nav.removeClass("alt");
      },
    });

    // Links.
    var $nav_a = $nav.find("a");

    $nav_a
      .on("click", function () {
        var $this = $(this);

        // External link? Bail.
        if ($this.attr("href").charAt(0) != "#") return;

        // Deactivate all links.
        $nav_a.removeClass("active").removeClass("active-locked");

        // Activate link *and* lock it (so Scrollex doesn't try to activate other links as we're scrolling to this one's section).
        $this.addClass("active").addClass("active-locked");
      })
      .each(function () {
        var $this = $(this),
          id = $this.attr("href"),
          $section = $(id);

        // No section for this link? Bail.
        if ($section.length < 1) return;

        // Scrollex.
        $section.scrollex({
          mode: "middle",
          initialize: function () {
            // Deactivate section.
            if (browser.canUse("transition")) $section.addClass("inactive");
          },
          enter: function () {
            // Activate section.
            $section.removeClass("inactive");

            // No locked links? Deactivate all links and activate this section's one.
            if ($nav_a.filter(".active-locked").length == 0) {
              $nav_a.removeClass("active");
              $this.addClass("active");
            }

            // Otherwise, if this section's link is the one that's locked, unlock it.
            else if ($this.hasClass("active-locked"))
              $this.removeClass("active-locked");
          },
        });
      });

    // Move nav to the left side on scroll
    $window.on("scroll", function () {
      if ($window.scrollTop() > $nav.height()) {
        $nav.addClass("fixed-left");
      } else {
        $nav.removeClass("fixed-left");
      }
    });
  }
})(jQuery);

document.addEventListener('DOMContentLoaded', async () => {
	try {
		const response = await fetch('data.json');
		const data = await response.json();

		const personList = document.getElementById('person-list');
		const benchmarkTableBody = document.getElementById('benchmark-table-body');

		const renderBenchmarks = (benchmarks) => {
			benchmarkTableBody.innerHTML = '';
			benchmarks.forEach(benchmark => {
				const tr = document.createElement('tr');
				tr.innerHTML = `
					<td>${new Date().getFullYear()}</td>
					<td>${benchmark.quarter}</td>
					<td>${benchmark.squat}</td>
					<td>${benchmark.benchPress}</td>
					<td>${benchmark.deadlift}</td>
				`;
				benchmarkTableBody.appendChild(tr);
			});
		};

		data.people.forEach(person => {
			// Populate the sidebar list
			const li = document.createElement('li');
			li.textContent = person.name;
			li.addEventListener('click', () => {
				renderBenchmarks(person.benchmarks);
			});
			personList.appendChild(li);
		});

		// Initially render the first person's benchmarks
		if (data.people.length > 0) {
			renderBenchmarks(data.people[0].benchmarks);
		}
	} catch (error) {
		console.error('Error fetching benchmarks:', error);
	}
});


