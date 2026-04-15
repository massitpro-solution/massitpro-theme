(function () {
	const header = document.querySelector('[data-site-header]');
	const mobileToggle = document.querySelector('[data-mobile-toggle]');
	const mobilePanel = document.querySelector('[data-mobile-panel]');

	const syncHeader = function () {
		if (! header) {
			return;
		}

		header.classList.toggle('is-scrolled', window.scrollY > 20);
	};

	const closeMobilePanel = function () {
		if (! mobileToggle || ! mobilePanel) {
			return;
		}

		mobileToggle.classList.remove('is-open');
		mobileToggle.setAttribute('aria-expanded', 'false');
		mobilePanel.classList.remove('is-open');
		mobilePanel.hidden = true;
		document.body.classList.remove('has-mobile-menu');
	};

	const openMobilePanel = function () {
		if (! mobileToggle || ! mobilePanel) {
			return;
		}

		mobileToggle.classList.add('is-open');
		mobileToggle.setAttribute('aria-expanded', 'true');
		mobilePanel.hidden = false;
		mobilePanel.classList.add('is-open');
		document.body.classList.add('has-mobile-menu');
	};

	if (header) {
		syncHeader();
		window.addEventListener('scroll', syncHeader, { passive: true });
	}

	if (mobileToggle && mobilePanel) {
		mobileToggle.addEventListener('click', function () {
			if (mobilePanel.classList.contains('is-open')) {
				closeMobilePanel();
				return;
			}

			openMobilePanel();
		});

		mobilePanel.querySelectorAll('a').forEach(function (link) {
			link.addEventListener('click', closeMobilePanel);
		});

		window.addEventListener('resize', function () {
			if (window.innerWidth >= 1024) {
				closeMobilePanel();
			}
		});
	}

	const revealItems = document.querySelectorAll('[data-reveal]');
	if ('IntersectionObserver' in window && revealItems.length) {
		const observer = new IntersectionObserver(
			function (entries, watcher) {
				entries.forEach(function (entry) {
					if (! entry.isIntersecting) {
						return;
					}

					entry.target.classList.add('is-visible');
					watcher.unobserve(entry.target);
				});
			},
			{
				threshold: 0.1,
				rootMargin: '0px 0px -10% 0px'
			}
		);

		revealItems.forEach(function (item) {
			item.classList.add('reveal-item');
			observer.observe(item);
		});
	} else {
		revealItems.forEach(function (item) {
			item.classList.add('reveal-item', 'is-visible');
		});
	}

	document.querySelectorAll('[data-accordion-trigger]').forEach(function (trigger) {
		trigger.addEventListener('click', function () {
			const item = trigger.closest('.accordion-item');
			const group = item && item.parentElement ? item.parentElement : null;
			const willOpen = item ? ! item.classList.contains('is-open') : false;

			if (! item) {
				return;
			}

			if (group) {
				group.querySelectorAll('.accordion-item').forEach(function (entry) {
					entry.classList.remove('is-open');
					const entryTrigger = entry.querySelector('[data-accordion-trigger]');
					if (entryTrigger) {
						entryTrigger.setAttribute('aria-expanded', 'false');
					}
				});
			}

			item.classList.toggle('is-open', willOpen);
			trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
		});
	});

	document.querySelectorAll('[data-carousel]').forEach(function (carousel) {
		const key = carousel.getAttribute('data-carousel');
		const prev = key ? document.querySelector('[data-carousel-prev="' + key + '"]') : null;
		const next = key ? document.querySelector('[data-carousel-next="' + key + '"]') : null;

		if (! prev || ! next) {
			return;
		}

		const step = function () {
			return Math.max(carousel.clientWidth * 0.8, 320);
		};

		prev.addEventListener('click', function () {
			carousel.scrollBy({ left: step() * -1, behavior: 'smooth' });
		});

		next.addEventListener('click', function () {
			carousel.scrollBy({ left: step(), behavior: 'smooth' });
		});
	});

	const slider = document.querySelector('[data-testimonial-slider]');
	if (slider) {
		const slides = Array.prototype.slice.call(slider.querySelectorAll('[data-testimonial-slide]'));
		const dots = Array.prototype.slice.call(slider.querySelectorAll('[data-testimonial-dot]'));
		const prev = slider.querySelector('[data-testimonial-prev]');
		const next = slider.querySelector('[data-testimonial-next]');
		let index = 0;

		const syncSlides = function () {
			slides.forEach(function (slide, slideIndex) {
				slide.classList.toggle('is-active', slideIndex === index);
			});

			dots.forEach(function (dot, dotIndex) {
				dot.classList.toggle('is-active', dotIndex === index);
			});
		};

		if (prev) {
			prev.addEventListener('click', function () {
				index = (index - 1 + slides.length) % slides.length;
				syncSlides();
			});
		}

		if (next) {
			next.addEventListener('click', function () {
				index = (index + 1) % slides.length;
				syncSlides();
			});
		}

		dots.forEach(function (dot, dotIndex) {
			dot.addEventListener('click', function () {
				index = dotIndex;
				syncSlides();
			});
		});

		syncSlides();
	}

	const filterButtons = document.querySelectorAll('.filter-btn');
	if (filterButtons.length) {
		filterButtons.forEach(function (button) {
			button.addEventListener('click', function () {
				const filter = button.getAttribute('data-filter') || 'all';

				filterButtons.forEach(function (entry) {
					entry.classList.toggle('is-active', entry === button);
				});

				document.querySelectorAll('.testimonial-card').forEach(function (card) {
					const industry = card.getAttribute('data-industry') || '';

					if ('all' === filter || industry === filter) {
						card.hidden = false;
						card.style.display = '';
					} else {
						card.hidden = true;
						card.style.display = 'none';
					}
				});
			});
		});
	}
})();
