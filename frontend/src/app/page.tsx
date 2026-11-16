import Link from 'next/link';

type Product = { id: number; name: string; slug: string; shortDescription?: string | null; description?: string | null; images?: { image: string; altText?: string | null }[]; category?: { name: string } };
const API = (process.env.API_INTERNAL_URL || process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api/v1').replace(/\/$/, '');

async function getProducts(): Promise<Product[]> {
  try {
    const response = await fetch(`${API}/products?limit=8&status=published`, { next: { revalidate: 120 } });
    if (!response.ok) return [];
    const payload = await response.json();
    return Array.isArray(payload) ? payload : payload.data || payload.items || [];
  } catch { return []; }
}

export default async function HomePage() {
  const products = await getProducts();
  return <main>
    <div className="announcement">A little brilliance for every moment <span>✦</span> Discover the Royal Shine collection</div>
    <header className="site-header">
      <Link className="brand" href="/">ROYAL <span>SHINE</span><small>JEWELLERS</small></Link>
      <nav><Link href="/about-us">Our Story</Link><Link href="/jewellery">Jewellery</Link><Link href="/collections">Collections</Link><Link href="/gallery">Gallery</Link><Link href="/contact-us">Contact</Link></nav>
      <Link className="header-cta" href="/contact-us">Enquire now ↗</Link>
    </header>
    <section className="hero">
      <div className="hero-copy"><p className="eyebrow">THE ART OF ADORNMENT</p><h1>Every piece tells<br/><em>your story.</em></h1><p className="hero-text">Discover jewellery that celebrates your moments, your milestones and everything that makes you beautifully you.</p><div className="hero-actions"><Link className="button button-dark" href="/jewellery">Explore jewellery <span>↗</span></Link><Link className="text-link" href="/about-us">Our story <span>→</span></Link></div><div className="hero-note"><span>✳</span> Crafted for moments that matter</div></div>
      <div className="hero-art" role="img" aria-label="Elegant gold jewellery editorial composition"><div className="art-orbit orbit-one"/><div className="art-orbit orbit-two"/><div className="art-stone">R</div><div className="art-caption">TIMELESS BY DESIGN<br/><b>ROYAL SHINE</b></div></div>
    </section>
    <section className="intro section-wrap"><p className="eyebrow">A WORLD OF WONDER</p><h2>Find the piece that feels<br/><em>like it was made for you.</em></h2><p>From everyday elegance to unforgettable celebrations, explore designs chosen to make every moment shine.</p></section>
    <section className="collection-section section-wrap"><div className="section-heading"><div><p className="eyebrow">CURATED FOR YOU</p><h2>Featured <em>pieces</em></h2></div><Link className="text-link" href="/jewellery">View all jewellery →</Link></div>
      {products.length ? <div className="product-grid">{products.map((product) => <Link className="product-card" href={`/product/${product.slug}`} key={product.id}><div className="product-image">{product.images?.[0]?.image ? <img src={product.images[0].image} alt={product.images[0].altText || product.name}/> : <span>ROYAL SHINE</span>}</div><p className="product-category">{product.category?.name || 'ROYAL COLLECTION'}</p><h3>{product.name}</h3><p className="product-desc">{product.shortDescription || product.description || 'Discover the details of this special design.'}</p></Link>)}</div> : <div className="empty-catalog"><span>✧</span><h3>Something beautiful is being curated.</h3><p>Our latest jewellery pieces will appear here soon. Until then, our team would love to help you find your perfect design.</p><Link className="button button-dark" href="/contact-us">Make an enquiry ↗</Link></div>}
    </section>
    <section className="story-band"><p className="eyebrow">THE ROYAL SHINE PROMISE</p><h2>Beauty in every detail.<br/><em>Meaning in every moment.</em></h2><Link className="button button-light" href="/about-us">Discover our story ↗</Link></section>
    <footer><Link className="brand footer-brand" href="/">ROYAL <span>SHINE</span><small>JEWELLERS</small></Link><p>Jewellery for your moments that matter.</p><div><Link href="/contact-us">Contact</Link><Link href="/privacy-policy">Privacy</Link><Link href="/terms-and-conditions">Terms</Link><Link href="/admin/login">Admin</Link></div><small>© {new Date().getFullYear()} Royal Shine. All rights reserved.</small></footer>
  </main>;
}
