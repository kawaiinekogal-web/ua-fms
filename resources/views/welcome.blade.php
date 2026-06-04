@extends('layouts.app')

@section('title', 'Welcome to UA Facility Management System')

@section('main-content-class', 'w-full')

@section('content')
<style>


/* ── DESIGN SYSTEM TOKENS (Strict Monochrome Palette) ── */
:root{
  --primary:#111111;
  --primary-dark:#000000;
  --primary-light:#E0E0E0;
  --bg:#ffffff;
  --bg2:#FAFAFA;
  --bg3:#F5F5F5;
  --text:#111111;
  --text2:#4D4D4D;
  --text3:#808080;
  --border:#CCCCCC;
  --border2:#E6E6E6;
  --sans:'DM Sans',sans-serif;
  --display:'DM Serif Display',serif;
  --r-sm:4px;
  --r-md:8px;
  --r-lg:12px;
  --r-xl:16px;
  --shadow-xs:0 1px 2px rgba(0,0,0,.05);
  --shadow-sm:0 2px 8px rgba(0,0,0,.07);
  --shadow-md:0 4px 16px rgba(0,0,0,.1);
}

*{box-sizing:border-box;margin:0;padding:0}
body{font-family:var(--sans);background:var(--bg);color:var(--text);font-size:14px;-webkit-font-smoothing:antialiased}
a{color:inherit;text-decoration:none}
input,select,textarea,button{font-family:inherit}

/* ── UNIFIED MAIN HEADER (Matches Image 2 Structure) ── */
.main-header{background:var(--primary);color:#fff;padding:0 48px;height:80px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;box-shadow:var(--shadow-sm)}
.header-left{display:flex;align-items:center;gap:16px}
.header-logo{width:54px;height:54px;object-fit:contain}
.header-titles{display:flex;flex-direction:column;justify-content:center}
.ht-uni{font-family:var(--display);font-size:22px;line-height:1.1;letter-spacing:0.5px}
.ht-sys{font-size:12px;color:#ccc;font-weight:500;margin-top:2px;letter-spacing:0.3px;text-transform:uppercase}

.header-right{display:flex;align-items:center;gap:32px}
.nav-links{display:flex;gap:24px}
.nav-link{font-size:13px;color:#ccc;cursor:pointer;transition:color .15s;font-weight:500}
.nav-link:hover{color:#fff}
.nav-actions{display:flex;gap:10px;align-items:center}
.btn-ghost{padding:7px 16px;border:1px solid rgba(255,255,255,0.4);border-radius:var(--r-md);font-size:13px;font-weight:600;cursor:pointer;background:transparent;color:#fff;transition:all .15s}
.btn-ghost:hover{background:rgba(255,255,255,0.1);border-color:#fff}
.btn-solid{padding:7px 18px;border:1px solid #fff;border-radius:var(--r-md);font-size:13px;font-weight:600;cursor:pointer;background:#fff;color:var(--primary);transition:all .15s}
.btn-solid:hover{background:#e6e6e6}

/* ── AUTH BAR ── */
.auth-bar{background:var(--bg2);border-bottom:1px solid var(--border2);padding:8px 48px;display:flex;align-items:center;justify-content:space-between;font-size:12px;color:var(--text)}
.auth-bar a{color:var(--primary);font-weight:600;margin-left:12px;text-decoration:underline}

/* ── HERO SECTION (Static, No Carousel) ── */
.hero-section{position:relative;width:100%;height:320px;background:#fff;display:flex;align-items:center;justify-content:center;border-bottom:1px solid var(--border2);padding:0 48px}
.hero-content{text-align:center;max-width:640px}
.hero-content h1{font-family:var(--display);color:var(--primary);font-size:42px;font-weight:400;line-height:1.2;margin-bottom:12px}
.hero-content p{color:var(--text2);font-size:16px;line-height:1.6}

/* ── HERO CAROUSEL (White Background, Black Text) ── */
.hero-carousel{position:relative;width:100%;height:460px;background:#fff;overflow:hidden;border-bottom:1px solid var(--border2)}
.slide{position:absolute;top:0;left:0;width:100%;height:100%;background-size:cover;background-position:center;opacity:0;filter:grayscale(100%)}
.slide.active{opacity:1}
.hero-overlay{position:absolute;bottom:0;left:0;width:100%;padding:120px 48px 40px;background:linear-gradient(to top, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0) 100%);display:flex;flex-direction:column;pointer-events:none}
.hero-overlay h2{font-family:var(--display);color:#fff;font-size:36px;font-weight:400;line-height:1.1;margin-bottom:6px}
.hero-overlay p{color:#ccc;font-size:15px;letter-spacing:0.5px}

/* ── FEATURES ── */
.section{
  padding: 60px 48px;
  max-width: 1200px;
  margin: 0 auto;
}
.section-head{text-align:center;margin-bottom:42px}
.section-head h2{font-family:var(--display);font-size:28px;font-weight:400;margin-bottom:10px}
.section-head p{font-size:14px;color:var(--text2);line-height:1.75}
.feat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
@media(max-width:768px){.feat-grid{grid-template-columns:1fr}}
.feat-card{background:#fff;border:1px solid var(--border2);border-radius:var(--r-xl);padding:24px;box-shadow:var(--shadow-xs);transition:box-shadow .2s,transform .2s}
.feat-card:hover{box-shadow:var(--shadow-md);transform:translateY(-2px)}
.feat-ico{width:44px;height:44px;border-radius:var(--r-md);background:var(--bg3);display:flex;align-items:center;justify-content:center;margin-bottom:14px}
.feat-ico svg{width:22px;height:22px;stroke:var(--primary);fill:none;stroke-width:1.6;stroke-linecap:round;stroke-linejoin:round}
.feat-card h3{font-size:14px;font-weight:600;margin-bottom:7px}
.feat-card p{font-size:12px;color:var(--text2);line-height:1.7}

/* ── STATS BAND ── */
.stats-band{background:var(--bg3);border-top:1px solid var(--border2);border-bottom:1px solid var(--border2);padding:48px}
.stats-inner{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;max-width:760px;margin:0 auto;text-align:center}
@media(max-width:640px){.stats-inner{grid-template-columns:repeat(2,1fr)}}
.stat-num{font-family:var(--display);font-size:34px;font-weight:400;color:var(--primary);margin-bottom:6px}
.stat-lbl{font-size:12px;color:var(--text2);font-weight:600;letter-spacing:.3px;text-transform:uppercase}

/* ── STRICT MONOCHROME PORTRAIT PORTAL SECTION ── */
.ua-portal-section {
}

.ua-portal-grid {
  display: grid;
  grid-template-columns: 0.9fr 1.1fr; /* Adjusts width ratio so cards aren't squished */
  gap: 64px; /* Increases the space between the text and the cards */
  align-items: start; /* FIX: Aligns text to the top instead of floating in the center */
}

@media (max-width: 992px) {
  .ua-portal-grid {
    grid-template-columns: 1fr;
    gap: 40px;
  }
}

/* Left Content: Text fixed to the side */
.ua-text-column {
  padding-top: 8px; /* Slight nudge to visually align with the top of the cards */
}

.ua-text-column h2 {
  font-family: var(--display, 'DM Serif Display', serif);
  font-size: 38px;
  color: var(--primary); /* Strict Black */
  margin-bottom: 16px;
  font-weight: 400;
  line-height: 1.1;
}

.ua-text-column p {
  font-size: 14.5px;
  color: var(--text2); /* Gray */
  line-height: 1.8;
  text-align: left; /* Makes the paragraph look cleaner like a block */
}

/* Right Content: 3 Portrait Cards */
.ua-portrait-cards {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
}

@media (max-width: 640px) {
  .ua-portrait-cards {
    grid-template-columns: 1fr;
  }
}

/* Portrait Rectangle Card Styling */
.ua-portrait-card {
  background: #ffffff;
  border: 1px solid var(--border);
  padding: 32px 16px 24px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  text-align: center;
  text-decoration: none;
  color: var(--text);
  min-height: 240px;
  transition: all 0.2s ease;
}

.ua-portrait-card:hover {
  border-color: var(--primary);
  background: var(--bg2);
}

.ua-card-text h3 {
  font-size: 13px;
  font-weight: 600;
  margin-bottom: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: var(--primary);
}

.ua-card-text p {
  font-size: 11px;
  color: var(--text3);
  line-height: 1.5;
}

/* Simplest Plain Text Login Button */
.ua-simple-btn {
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  color: var(--primary);
  border-bottom: 1px solid var(--primary);
  padding-bottom: 2px;
  align-self: center;
  margin-top: 24px;
  transition: opacity 0.2s;
}

.ua-portrait-card:hover .ua-simple-btn {
  opacity: 0.6;
}

/* ── FACILITIES ── */
/* ── STRICT MONOCHROME FACILITIES GRID ── */
/* ── STRICT MONOCHROME FACILITIES GRID ── */
/* ── MINIMALIST HORIZONTAL RECTANGLE FACILITIES GRID ── */
.ua-facilities-section {
  width: 100%;
  box-sizing: border-box;
}

.ua-section-header {
  margin-bottom: 32px;
  padding: 0 4px;
}

.ua-section-header h2 {
  font-family: var(--display, 'DM Serif Display', serif);
  font-size: 32px;
  color: var(--primary);
  margin-bottom: 8px;
  font-weight: 400;
}

.ua-section-header p {
  font-size: 14px;
  color: var(--text2);
}

/* Crisp 3-column layout that naturally scales */
.ua-facilities-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px; /* Uniform, balanced space between cards */
  width: 100%;
  box-sizing: border-box;
}

/* Tablet view transitions smoothly to 2 columns */
@media (max-width: 992px) {
  .ua-facilities-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Mobile view stacks into clean full-width rows */
@media (max-width: 640px) {
  .ua-facilities-grid {
    grid-template-columns: 1fr;
  }
  .ua-facilities-section {
    padding: 40px 16px;
  }
}

/* Normal Landscape Rectangle Card Style */
.ua-facility-card {
  background: #ffffff;
  border: 1px solid var(--border);
  padding: 24px; /* Balanced interior padding */
  box-sizing: border-box;
  transition: all 0.15s ease-in-out;
  display: flex;
  flex-direction: column;
  align-items: flex-start; /* FIX: Left-aligns text for the classic rectangle look */
  justify-content: flex-start;
  text-align: left;        /* FIX: Left-aligned minimalist text */
}

.ua-facility-card:hover {
  border-color: var(--primary); /* Turns black on hover */
  background: var(--bg2);
}

/* Facility Name Styling */
.ua-facility-card h4 {
  font-size: 13.5px;
  font-weight: 600;
  text-transform: uppercase;
  color: var(--primary);
  letter-spacing: 0.5px;
  margin: 0 0 8px 0; /* Tight, crisp spacing directly above description */
}

/* Facility Description Styling */
.ua-facility-desc {
  font-size: 12px;
  color: var(--text2);
  line-height: 1.5;
  margin: 0;
}

@media (max-width: 868px) {
  .ua-facilities-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 540px) {
  .ua-facilities-grid {
    grid-template-columns: 1fr;
  }
}

/* Individual Text-Only Facility Card */
.ua-facility-card {
  background: #ffffff;
  border: 1px solid var(--border);
  padding: 32px 24px; /* Increased padding for breathing room without images */
  transition: all 0.2s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  min-height: 140px; /* Keeps all cards uniformly sized */
}

.ua-facility-card:hover {
  border-color: var(--primary); /* Turns black on hover */
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

/* Facility Name Styling */
.ua-facility-card h4 {
  font-size: 14px;
  font-weight: 600;
  text-transform: uppercase;
  color: var(--primary);
  letter-spacing: 0.5px;
  margin: 0 0 10px 0;
}

/* Facility Description Styling */
.ua-facility-desc {
  font-size: 12px;
  color: var(--text2);
  line-height: 1.6;
  margin: 0;
}

/* ── FOOTER ── */
.footer{background:var(--primary);padding:48px;color:#fff;display:grid;grid-template-columns:1fr 1fr;gap:48px}
.footer-left img{width:64px;margin-bottom:16px;filter:brightness(0) invert(1)}
.footer h4{font-family:var(--display);font-size:20px;margin-bottom:8px}
.footer p{font-size:12px;color:#ccc;line-height:1.6}
.footer-right{text-align:right}
.footer-bottom{grid-column:1/-1;border-top:1px solid #333;padding-top:24px;margin-top:24px;display:flex;justify-content:space-between;font-size:11px;color:#999}
</style>

{{-- HERO CAROUSEL --}}
<div class="hero-carousel">
  <div class="slide active" style="background-image: url('{{ asset('img/facilities/busalian-hall.jpg') }}')"></div>
  <div class="slide" style="background-image: url('{{ asset('img/facilities/grand-stand.jpg') }}')"></div>
  <div class="slide" style="background-image: url('{{ asset('img/facilities/paghiusa-hall.jpg') }}')"></div>

  <div class="hero-overlay">
    <div style="max-width: 1040px; margin: 0 auto;">
      <h2>University of Antique</h2>
      <p>Facility Management System</p>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const slides = document.querySelectorAll('.hero-carousel .slide');
  let currentSlide = 0;

  setInterval(() => {
    slides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide + 1) % slides.length;
    slides[currentSlide].classList.add('active');
  }, 4000); // Change slide every 4 seconds
});
</script>

<div class="ua-portal-section" id="about">
  <div class="ua-portal-grid">

    <div class="ua-text-column">
      <h2>Kruhay Bambuhay!</h2>
      <p>
        Welcome to the University of Antique Facility Management System. The General Services Unit (GSU)
        is committed to providing top-tier logistical support, preventive maintenance, and administrative assistance
        across campus facilities. Our services ensure that physical spaces, venue reservations, and event systems
        run seamlessly to promote a productive academic and communal life for all internal organizational units
        and college personnel.
      </p>
    </div>

    <div class="ua-portrait-cards">

      <a href="{{ route('login') }}" class="ua-portrait-card">
        <div class="ua-card-text">
          <h3>Admin<br>Login</h3>
          <p>System configuration & general services controls</p>
        </div>
        <span class="ua-simple-btn">Log In</span>
      </a>

      <a href="{{ route('login') }}" class="ua-portrait-card">
        <div class="ua-card-text">
          <h3>College<br>Staff</h3>
          <p>Departmental venue reservations & access</p>
        </div>
        <span class="ua-simple-btn">Log In</span>
      </a>

      <a href="{{ route('login') }}" class="ua-portrait-card">
        <div class="ua-card-text">
          <h3>Org<br>Unit</h3>
          <p>Student org requests & event scheduling</p>
        </div>
        <span class="ua-simple-btn">Log In</span>
      </a>

    </div>

  </div>
</div>

{{-- PUBLIC CALENDAR (wrapped existing calendar blade in a div) --}}
<div id="calendar" style="margin-top: 40px;">
    @include('public.calendar')
</div>





{{-- FACILITIES --}}
<div class="ua-facilities-section" id="facilities">
  <div class="ua-section-header">
    <h2>Key Facilities</h2>
    <p>Core venues and spaces available through the University of Antique Facility Management System.</p>
  </div>

  <div class="ua-facilities-grid">

    <div class="ua-facility-card">
      <h4>E-Hub</h4>
    <p class="ua-facility-desc">Modern digital innovation center equipped with workstations for tech events and workshops.</p>
  </div>

  <div class="ua-facility-card">
    <h4>Paghiusa Hall</h4>
    <p class="ua-facility-desc">A large audio-visual hall ideal for university-wide seminars and major presentations.</p>
  </div>

  <div class="ua-facility-card">
    <h4>Tiripunan Hall</h4>
    <p class="ua-facility-desc">A versatile gathering space designed for student organization meetings and collaborative activities.</p>
  </div>

  <div class="ua-facility-card">
    <h4>Busalian Hall</h4>
    <p class="ua-facility-desc">The main multi-purpose hall for large assemblies, graduations, and formal ceremonies.</p>
  </div>

  <div class="ua-facility-card">
    <h4>CIT AVR</h4>
    <p class="ua-facility-desc">Dedicated audio-visual room for the College of Industrial Technology's lectures and defenses.</p>
  </div>

  <div class="ua-facility-card">
    <h4>CBA AVR</h4>
    <p class="ua-facility-desc">Premier audio-visual facility for the College of Business Administration's seminars and conferences.</p>
  </div>

  <div class="ua-facility-card">
    <h4>Grandstand</h4>
    <p class="ua-facility-desc">Open-air seating and field area for university sports events, intramurals, and outdoor gatherings.</p>
  </div>

  <div class="ua-facility-card">
    <h4>Balay ni Juan</h4>
    <p class="ua-facility-desc">A cultural and heritage venue suited for intimate gatherings, art exhibits, and special receptions.</p>
  </div>

  <div class="ua-facility-card">
    <h4>New AVR</h4>
    <p class="ua-facility-desc">Newly upgraded audio-visual room featuring state-of-the-art projection and sound systems.</p>
  </div>

</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const slides = document.querySelectorAll('.hero-carousel .slide');
    let current = 0;

    if (!slides.length) return;

    // Ensure only the first slide is active initially
    slides.forEach((s, i) => s.classList.toggle('active', i === 0));

    if (slides.length === 1) return; // nothing to rotate

    setInterval(() => {
      slides[current].classList.remove('active');
      current = (current + 1) % slides.length;
      slides[current].classList.add('active');
    }, 5000); // 5 seconds
  });
</script>

@endsection