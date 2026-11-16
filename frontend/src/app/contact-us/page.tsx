import Link from 'next/link';
import ContactForm from './ContactForm';

export const metadata = {
  title: 'Contact Us | Royal Shine',
  description: 'Get in touch with Royal Shine for jewellery enquiries.',
};

export default function ContactPage() {
  return <ContactForm />;
}
