<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Dinh Dưỡng - TQFood</title>
    <link rel="stylesheet" href="/CoSo/css/reset.css">
    <link rel="stylesheet" href="/CoSo/css/general.css">
    <link rel="stylesheet" href="/CoSo/css/index.css">
    <link rel="stylesheet" href="/CoSo/css/blog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>

    <!-- Hero Blog -->
    <section class="blog_hero">
        <div class="container">
            <div class="blog_hero_content" data-aos="fade-up">
                <h1>Blog Dinh Dưỡng</h1>
                <p>Khám phá những kiến thức bổ ích về dinh dưỡng và ẩm thực từ đội ngũ chuyên gia hàng đầu</p>
            </div>
        </div>
    </section>

    <!-- Featured Chef -->
    <section class="featured_chef">
        <div class="container">
            <div class="chef_intro" data-aos="fade-up">
                <div class="chef_avatar_large">
                    <img src="../assets/img/Chef/about.png.webp" alt="Chef chuyên gia">
                </div>
                <div class="chef_intro_content">
                    <h2>Chef Nguyễn Minh Tuấn</h2>
                    <p class="chef_title">Chuyên gia Dinh dưỡng & Trưởng bếp TQFood</p>
                    <p class="chef_bio">Với hơn 15 năm kinh nghiệm trong lĩnh vực ẩm thực và dinh dưỡng, Chef Tuấn đã giúp hàng nghìn người cải thiện sức khỏe thông qua chế độ ăn uống khoa học.</p>
                    <div class="chef_credentials">
                        <span class="credential">🏆 Chứng chỉ Dinh dưỡng Quốc tế</span>
                        <span class="credential">📚 Tác giả 20+ bài nghiên cứu</span>
                        <span class="credential">👨‍🍳 15+ năm kinh nghiệm</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Posts -->
    <section class="blog_posts">
        <div class="container">
            <h2 class="section_title" data-aos="fade-up">Bài Viết Mới Nhất</h2>
            
            <div class="blog_grid">
                <!-- Blog Post 1 -->
                <article class="blog_post featured" data-aos="fade-up" data-aos-delay="100">
                    <div class="post_image">
                        <img src="../assets/img/Chef/blog1.png.webp" alt="Bí quyết nấu ăn healthy">
                        <div class="post_category">Mẹo nấu ăn</div>
                    </div>
                    <div class="post_content">
                        <h3>Bí Quyết Nấu Ăn Healthy Từ Chef Chuyên Nghiệp</h3>
                        <p class="post_excerpt">Khám phá những mẹo nhỏ nhưng quan trọng để biến món ăn thường ngày thành những bữa ăn dinh dưỡng hoàn hảo. Từ việc lựa chọn nguyên liệu đến kỹ thuật chế biến...</p>
                        <div class="post_meta">
                            <span class="post_date"><i class="fas fa-calendar"></i> 15/07/2025</span>
                            <span class="post_author"><i class="fas fa-user"></i> Chef Tuấn</span>
                            <span class="read_time"><i class="fas fa-clock"></i> 5 phút đọc</span>
                        </div>
                        <a href="#" class="read_more">Đọc tiếp →</a>
                    </div>
                </article>

                <!-- Blog Post 2 -->
                <article class="blog_post" data-aos="fade-up" data-aos-delay="200">
                    <div class="post_image">
                        <img src="../assets/img/Chef/blog2.png.webp" alt="Cân bằng dinh dưỡng">
                        <div class="post_category">Dinh dưỡng</div>
                    </div>
                    <div class="post_content">
                        <h3>Cách Cân Bằng Dinh Dưỡng Trong Mỗi Bữa Ăn</h3>
                        <p class="post_excerpt">Hướng dẫn chi tiết về cách phân bổ các nhóm chất dinh dưỡng để tạo ra những bữa ăn cân bằng, đầy đủ và phù hợp với mục tiêu sức khỏe của bạn...</p>
                        <div class="post_meta">
                            <span class="post_date"><i class="fas fa-calendar"></i> 12/07/2025</span>
                            <span class="post_author"><i class="fas fa-user"></i> Chef Tuấn</span>
                            <span class="read_time"><i class="fas fa-clock"></i> 7 phút đọc</span>
                        </div>
                        <a href="#" class="read_more">Đọc tiếp →</a>
                    </div>
                </article>

                <!-- Blog Post 3 -->
                <article class="blog_post" data-aos="fade-up" data-aos-delay="300">
                    <div class="post_image">
                        <img src="../assets/img/Chef/blog3.png.webp" alt="Thực đơn giảm cân">
                        <div class="post_category">Giảm cân</div>
                    </div>
                    <div class="post_content">
                        <h3>Thực Đơn Giảm Cân Hiệu Quả Cho Người Bận Rộn</h3>
                        <p class="post_excerpt">Những công thức món ăn giảm cân đơn giản, nhanh gọn nhưng vẫn đảm bảo đầy đủ dinh dưỡng, phù hợp với lối sống hiện đại...</p>
                        <div class="post_meta">
                            <span class="post_date"><i class="fas fa-calendar"></i> 10/07/2025</span>
                            <span class="post_author"><i class="fas fa-user"></i> Chef Tuấn</span>
                            <span class="read_time"><i class="fas fa-clock"></i> 6 phút đọc</span>
                        </div>
                        <a href="#" class="read_more">Đọc tiếp →</a>
                    </div>
                </article>

                <!-- Additional Blog Posts using same images creatively -->
                <article class="blog_post" data-aos="fade-up" data-aos-delay="400">
                    <div class="post_image">
                        <img src="../assets/img/Chef/blog1.png.webp" alt="Nguyên liệu tự nhiên">
                        <div class="post_category">Nguyên liệu</div>
                    </div>
                    <div class="post_content">
                        <h3>Cách Chọn Nguyên Liệu Tự Nhiên Chất Lượng</h3>
                        <p class="post_excerpt">Bí quyết nhận biết và lựa chọn những nguyên liệu tự nhiên, tươi ngon nhất để tạo ra những món ăn an toàn và bổ dưỡng...</p>
                        <div class="post_meta">
                            <span class="post_date"><i class="fas fa-calendar"></i> 08/07/2025</span>
                            <span class="post_author"><i class="fas fa-user"></i> Chef Tuấn</span>
                            <span class="read_time"><i class="fas fa-clock"></i> 4 phút đọc</span>
                        </div>
                        <a href="#" class="read_more">Đọc tiếp →</a>
                    </div>
                </article>

                <article class="blog_post" data-aos="fade-up" data-aos-delay="500">
                    <div class="post_image">
                        <img src="../assets/img/Chef/blog2.png.webp" alt="Thực đơn cho trẻ em">
                        <div class="post_category">Gia đình</div>
                    </div>
                    <div class="post_content">
                        <h3>Thực Đơn Dinh Dưỡng Cho Trẻ Em</h3>
                        <p class="post_excerpt">Hướng dẫn tạo ra những bữa ăn hấp dẫn, đầy đủ dinh dưỡng mà trẻ em yêu thích, giúp bé phát triển khỏe mạnh...</p>
                        <div class="post_meta">
                            <span class="post_date"><i class="fas fa-calendar"></i> 05/07/2025</span>
                            <span class="post_author"><i class="fas fa-user"></i> Chef Tuấn</span>
                            <span class="read_time"><i class="fas fa-clock"></i> 8 phút đọc</span>
                        </div>
                        <a href="#" class="read_more">Đọc tiếp →</a>
                    </div>
                </article>

                <article class="blog_post" data-aos="fade-up" data-aos-delay="600">
                    <div class="post_image">
                        <img src="../assets/img/Chef/blog3.png.webp" alt="Chế độ ăn cho người tập gym">
                        <div class="post_category">Thể thao</div>
                    </div>
                    <div class="post_content">
                        <h3>Chế Độ Ăn Tối Ưu Cho Người Tập Gym</h3>
                        <p class="post_excerpt">Những món ăn giàu protein và năng lượng giúp tăng cường hiệu quả tập luyện và phục hồi cơ bắp nhanh chóng...</p>
                        <div class="post_meta">
                            <span class="post_date"><i class="fas fa-calendar"></i> 03/07/2025</span>
                            <span class="post_author"><i class="fas fa-user"></i> Chef Tuấn</span>
                            <span class="read_time"><i class="fas fa-clock"></i> 9 phút đọc</span>
                        </div>
                        <a href="#" class="read_more">Đọc tiếp →</a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="newsletter">
        <div class="container">
            <div class="newsletter_content" data-aos="fade-up">
                <h2>Đăng Ký Nhận Tin Mới</h2>
                <p>Nhận những bài viết mới nhất về dinh dưỡng và ẩm thực từ đội ngũ chuyên gia</p>
                <form class="newsletter_form">
                    <input type="email" placeholder="Nhập email của bạn">
                    <button type="submit">Đăng ký</button>
                </form>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <!-- AOS Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            offset: 100,
            once: true
        });
    </script>
</body>
</html>
