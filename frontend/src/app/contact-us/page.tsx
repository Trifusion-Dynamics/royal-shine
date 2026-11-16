'use client';
import { FormEvent, useEffect, useState } from 'react';
import Link from 'next/link';

type EnquiryForm = { name: string; mobile: string; email: string; subject: string; message: string };
const emptyForm: EnquiryForm = { name: '', mobile: '', email: '', subject: 'General enquiry', message: '' };

export default function Contact() {
  const [form, setForm] = useState<EnquiryForm>(emptyForm);
  const [product, setProduct] = useState('');
  const [state, setState] = useState<'idle'|'loading'|'success'|'error'>('idle');
  const [error, setError] = useState('');
  useEffect(() => {
    const q = new URLSearchParams(window.location.search);
    const selected = q.get('product') || '';
    const sku = q.get('sku') || '';
    setProduct(selected);
    if (selected) setForm((current) => ({ ...current, subject: 'Product enquiry', message: `I am interested in ${selected} (Reference: ${sku || 'N/A'}).` }));
  }, []);

  async function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault(); setState('loading'); setError('');
    try {
      const base = (process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api/v1').replace(/\/$/, '');
      const response = await fetch(`${base}/enquiries`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ ...form, enquiryType: product ? 'product' : 'contact' }) });
      const data = await response.json().catch(() => ({}));
      if (!response.ok) throw new Error(Array.isArray(data.message) ? data.message.join(', ') : data.message || 'Unable to submit your enquiry.');
      setState('success'); setForm(emptyForm);
    } catch (err) { setError(err instanceof Error ? err.message : 'Please try again later.'); setState('error'); }
  }

  return <main><header className="site-header"><Link className="brand" href="/">ROYAL <span>SHINE</span><small>JEWELLERS</small></Link><nav><Link href="/">Home</Link><Link href="/jewellery">Jewellery</Link><Link href="/about-us">Our Story</Link></nav></header><section className="section-wrap" style={{display:'grid',gridTemplateColumns:'minmax(240px,.8fr) minmax(280px,1.2fr)',gap:60}}><div><p className="eyebrow">WE WOULD LOVE TO HEAR FROM YOU</p><h1 style={{font:'52px Playfair Display,serif',fontWeight:400}}>Let's make<br/><em>it personal.</em></h1><p style={{color:'#756c62',lineHeight:1.9}}>Tell us what you are looking for. Our team will be happy to guide you through our collections and answer your questions.</p><p style={{marginTop:35,fontSize:12}}>Prefer WhatsApp? Contact our team directly through the number listed in our store details.</p></div><form onSubmit={submit} style={{background:'#f7f3ed',padding:30}}><h2 style={{font:'28px Playfair Display,serif',fontWeight:400}}>Send an enquiry</h2>{([['name','Your name','text'],['mobile','Mobile number','tel'],['email','Email address','email'],['subject','Subject','text']] as const).map(([key,label,type])=><label key={key} style={{display:'block',fontSize:11,marginTop:17}}>{label}<input required={key==='name'||key==='mobile'} type={type} value={form[key]} onChange={e=>setForm({...form,[key]:e.target.value})} style={{display:'block',width:'100%',padding:13,marginTop:7,border:'1px solid #e1d8cc',background:'#fffdf9'}}/></label>)}<label style={{display:'block',fontSize:11,marginTop:17}}>Message<textarea required value={form.message} onChange={e=>setForm({...form,message:e.target.value})} rows={4} style={{display:'block',width:'100%',padding:13,marginTop:7,border:'1px solid #e1d8cc',background:'#fffdf9'}}/></label>{error&&<p role="alert" style={{color:'#b42318',fontSize:12}}>{error}</p>}{state==='success'&&<p role="status" style={{color:'#24734b',fontSize:12}}>Thank you. Your enquiry has been received.</p>}<button disabled={state==='loading'} className="button button-dark" style={{marginTop:20,border:0,cursor:'pointer'}}>{state==='loading'?'Sending…':'Submit enquiry ↗'}</button></form></section></main>;
}
