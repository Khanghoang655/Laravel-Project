<footer class="footer-section">
    <div class="newsletter-section">
        <div class="container">
            <div class="newsletter-container">
                <div class="newsletter-wrapper">
                    <h5 class="cate">subscribe now</h5>
                    <h3 class="title">to get latest update</h3>
                    <form class="newsletter-form" action="{{route('potential')}}" method="POST">
                        @csrf
                        <input type="text" placeholder="Your Email Address" name="email" required>
                        <button type="submit">subscribe</button>
                    </form>
                    <p>We send you latest update and news to your email</p>
                </div>
            </div>
        </div>
    </div>
