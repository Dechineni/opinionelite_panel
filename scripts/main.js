document.addEventListener('DOMContentLoaded', () => {
  // Hide preloader with a fade effect
  const preloader = document.getElementById('preloader');
  console.log('Preloader found!????');
  if (preloader) {
    console.log('Preloader found!');
    // Add fade-out class
    preloader.classList.add('opacity-0');
    // Remove preloader after transition
    setTimeout(() => {
      preloader.style.display = 'none';
    }, 400); // match this with your transition duration
  }
  isSafari();

  initLegalModals();

  console.log('Application loaded!');
});

function isSafari() {
  if (/^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
    document.documentElement.classList.add('safari');
  }
}

function initLegalModals() {
  const modal = document.getElementById('legal-modal');
  const modalTitle = document.getElementById('modal-title');
  const modalContent = document.getElementById('modal-content');
  const closeButton = document.getElementById('close-modal');

  // Modal content
  const modalContents = {
    privacy: {
      title: 'Privacy Policy',
      content: `<p class="mb-4">Last updated: December 2024</p>
                <p class="mb-4">This Privacy Policy describes how QuantifyAI ("we," "us," or "our") collects, uses, and discloses your personal information when you use our services.</p>
                <p class="mb-4">1. Introduction</p>

<p class="mb-4">This "Privacy Policy" is for residents and persons located within the United States and Canada. For all other visitors, please visit the international version of our Privacy Policy here.</p>

<p class="mb-4">We want you to be familiar with how we collect, use, and share your Personally Identifiable Information (defined below). This Privacy Policy outlines the type of information that we collect and receive from and about you via the QuantifyAI Sites and Features and our Services (both as defined below), and our data practices related thereto, with additional disclosures for California, Colorado, Connecticut, Nevada, Utah and Virginia residents in the U.S. State Privacy Notice section below.</p>

<p class="mb-4">To the extent that there is a conflict between this Privacy Policy and the U.S. State Privacy Notice section, the U.S. State Privacy Notice section will control as to residents of those states.</p>

<p class="mb-4">Please review this Privacy Policy carefully, especially before providing any Personally Identifiable Information through the QuantifyAI Sites and Features or our Services. The QuantifyAI Sites and Features and our Services are generally operated in and controlled from the United States of America unless otherwise stated.</p>

<p class="mb-4">QuantifyAI Sites and Features and our Services may collect and use location-aware and cross-device data for advertising and other purposes.</p>

<p class="mb-4">IF YOU DO NOT WISH TO HAVE US COLLECT, USE, AND SHARE INFORMATION AS DESCRIBED IN THIS PRIVACY POLICY, PLEASE DO NOT USE ANY OF THE QuantifyAI SITES AND FEATURES OR OUR SERVICES.</p>

<p class="mb-4">Notice of Terms of Use, Including Arbitration: Your use of the QuantifyAI Sites and Features and our Services is subject to our Terms of Use, which includes binding individual arbitration of any disputes which may arise in connection with such use. Please note that your use of the QuantifyAI Sites and Features or our Services constitutes your express agreement to our Terms of Use, including its arbitration provisions and class action waiver. Please read the Terms of Use—including the arbitration provisions—carefully, and do not use any of the QuantifyAI Sites and Features or our Services if you do not agree.</p>

<p class="mb-4">2. Information We Collect</p>

<p class="mb-4">When you visit any QuantifyAI Sites and Features or use any of our Services, you may share and/or we may automatically collect information that identifies you personally. In this Privacy Policy, "Personally Identifiable Information" (or "PII") refers to any information that can reasonably be used to identify, contact or locate you.</p>

<p class="mb-4">Examples of PII may include, without limitation:</p>
<ul class="list-disc pl-8 mb-4">
  <li>Your name</li>
  <li>Your precise geo-location</li>
  <li>Your credit card number</li>
  <li>Your email address</li>
  <li>Your mailing address</li>
  <li>Your phone number</li>
</ul>

<p class="mb-4">We also collect, and may create from PII, information about you that is not PII ("Non-PII"). If we combine PII collected via QuantifyAI Sites and Features or our Services with other of your PII or with Non-PII, that combined data will be treated as PII subject to this Privacy Policy.</p>

<p class="mb-4">3. How We Use Your Information</p>

<p class="mb-4">We use your PII and non-PII to:</p>
<ul class="list-disc pl-8 mb-4">
  <li>Provide you with our Services</li>
  <li>Operate and improve the QuantifyAI Sites and Features</li>
  <li>Provide advertising, content, surveys, location-based deals, special offers, promotions, and other rewards opportunities</li>
  <li>Marketing, administrative, operational, business, and commercial purposes subject to applicable law</li>
</ul>

<p class="mb-4">4. Information Security</p>

<p class="mb-4">We maintain security measures to help protect against the loss, misuse and alteration of the PII and other information under our control. Please be advised, however, that the Internet and other technologies are, by their nature, not entirely secure, and your PII and non-PII may therefore be subject to interception or loss which is beyond our reasonable control.</p>

<p class="mb-4">5. Contact Us</p>

<p class="mb-4">If you have any questions about this Privacy Policy or our privacy practices, please contact us at our Help Center or through the contact options located in the applicable QuantifyAI Sites and Features footer.</p><p class="mb-4">
QuantifyAI, LLC<br>
3200 Paseo Village Way #2337<br>
San Diego, CA 92130<br>
USA
</p>`,
    },
    terms: {
      title: 'Terms of Use',
      content: `<p class="mb-4">Last updated: December 2024</p>
                <p class="mb-4 text-xl font-bold">Terms of Use</p>

<p class="mb-4 text-lg font-bold">NOTICE OF ARBITRATION PROVISIONS</p>

<p class="mb-4">Your use of the QuantifyAI Sites and Features and our Services (both as defined below) is subject to binding individual arbitration of any disputes which may arise, including a class action waiver, as provided in Section 11 of these Terms of Use. Please read the arbitration provisions carefully and do not use any of the QuantifyAI Sites and Features or our Services if you are unwilling to arbitrate any disputes you may have with us (including without limitation any disputes relating to these Terms of Use and our Privacy Policy) as provided herein.</p>

<p class="mb-4 text-lg font-bold">INTRODUCTION</p>

<p class="mb-4">QuantifyAI, LLC (together with any affiliates, the "Company") owns and operates a number of different websites, mobile applications, and interactive services, including without limitation Opinion Elite, QuantifyAI.co, and others (collectively, the "QuantifyAI Sites").</p>

<p class="mb-4">These Terms of Use ("Terms") apply to the QuantifyAI Sites and to all of the features, Internet browser extensions, emails, text (SMS) messages, online services and other functionalities (collectively, the "Features") available via or related to the QuantifyAI Sites, whether accessed via a computer, mobile device or other devices you use (each a "Device" and collectively, "Devices"), or otherwise (collectively, the "QuantifyAI Sites and Features"), and all services available on or through the QuantifyAI Sites and Features ("our Services").</p>

<p class="mb-4">These Terms are a legal agreement between you and the Company. By using any of the QuantifyAI Sites and Features or our Services, or clicking to "Accept" or otherwise agreeing to these Terms where that option is made available to you, you agree to be bound by these Terms. If you do not agree to these Terms, please do not register with or use any QuantifyAI Sites and Features or our Services.</p>

<p class="mb-8 text-lg font-bold">1. USE OF QuantifyAI SITES AND FEATURES</p>

<p class="mb-4">You agree to use the QuantifyAI Sites and Features and our Services only for purposes that are permitted by these Terms and any applicable law, regulation, or generally accepted practices in the relevant jurisdictions.</p>

<p class="mb-4">Subject to all of the provisions of these Terms, the Company hereby grants you a limited, terminable, non-transferable, personal, non-exclusive license to access and use the QuantifyAI Sites and Features and our Services solely as provided herein.</p>

<p class="mb-4">You may download material displayed on the QuantifyAI Sites and Features for non-commercial, personal use only, provided you do not remove any copyright and other proprietary notices contained on the materials. You may not, however, distribute, modify, broadcast, publicly perform, transmit, reuse, re-post, or use the content of the QuantifyAI Sites and Features, including any text, images, audio, or video, for public or commercial purposes without the Company's prior written permission.</p>

<p class="mb-8 text-lg font-bold">2. USER REPRESENTATIONS AND WARRANTIES</p>

<p class="mb-4">We prohibit anyone from using the QuantifyAI Sites and Features or Services who is under thirteen (13) years of age (or, if greater than 13, the minimum age applicable in your jurisdiction).</p>

<p class="mb-4">If you are under the age of eighteen (18) (or the legal age of majority in your jurisdiction), you represent by accessing the QuantifyAI Sites and Features or our Services that you have your parent's or legal guardian's approval to access them.</p>

<p class="mb-4">By using the QuantifyAI Sites and Features or Services, you confirm, represent, and warrant that:</p>

<ul class="list-disc pl-8 mb-4">
  <li class="mb-2">You are able to form a binding contract with QuantifyAI</li>
  <li class="mb-2">You are not subject to the prohibitions described in Excluded Users and Territories (Section 13) of these Terms</li>
  <li class="mb-2">You will comply with these Terms (including any Additional Terms) and all relevant local, state, national, and international laws, rules, and regulations</li>
</ul>

<p class="mb-8 text-lg font-bold">3. REWARDS PROGRAMS</p>

<p class="mb-4 font-bold">A. Overview</p>

<p class="mb-4">The Company may offer one or more rewards programs ("Rewards Programs") under which you may have the opportunity to receive points or other credits (collectively, "Rewards") related to your participation in or interaction with various advertising, content, shopping opportunities, special offers, surveys, coupons, location-based deals, and other Rewards opportunities (collectively, "Offers").</p>

<p class="mb-4 font-bold">B. Suspension or Termination</p>

<p class="mb-4">The Company may limit, suspend, or terminate your ability to participate in a Rewards Program, and may suspend or void any Rewards or potential Rewards you may have received or accumulated in a Rewards Program but not yet successfully redeemed, if we determine in our sole and absolute discretion that you have not complied with these Terms.</p>

<p class="mb-4 font-bold">C. Receiving Rewards</p>

<p class="mb-4">You may receive Rewards in a Rewards Program by participating in various Offers. Subject to the other provisions of these Terms, the Company will deposit any Rewards for Offers that you choose to participate in and successfully complete into your Account. Rewards are deemed successfully completed once you have fully and properly satisfied all of the requirements of the Offer in the manner specified.</p><p class="mb-8 text-lg font-bold">4. SWEEPSTAKES, CONTESTS AND PROMOTIONS</p>

<p class="mb-4">Any sweepstakes, contests, or promotions (collectively, "Promotions") that may be offered via any of the QuantifyAI Sites and Features or our Services may be governed by Additional Terms, including but not limited to official rules, which may set out eligibility requirements, such as certain age or geographic area restrictions.</p>

<p class="mb-8 text-lg font-bold">5. INTELLECTUAL PROPERTY</p>

<p class="mb-4">You acknowledge that the QuantifyAI Sites and Features have been developed, compiled, prepared, revised, selected, and arranged by the Company and others through the expenditure of substantial time, effort and money and constitute valuable intellectual property and trade secrets of the Company and others.</p>

<p class="mb-4">The trademarks, logos, and service marks ("Marks") displayed on the QuantifyAI Sites and Features are the property of the Company or third parties and cannot be used without the written permission of the Company or the third party that owns the Marks.</p>

<p class="mb-8 text-lg font-bold">6. REPORTING COPYRIGHT INFRINGEMENT - DMCA POLICY</p>

<p class="mb-4">If you believe that any content found on or through the QuantifyAI Sites and Features infringes your copyright, you should notify us. To be effective, the notification must be in writing and contain the following:</p>

<ul class="list-disc pl-8 mb-4">
  <li class="mb-2">An electronic or physical signature of the person authorized to act on behalf of the copyright owner</li>
  <li class="mb-2">Description of the copyrighted work claimed to have been infringed</li>
  <li class="mb-2">Description of where the material that you claim is infringing is located</li>
  <li class="mb-2">Your physical mailing address, telephone number and email address</li>
  <li class="mb-2">A statement that you have a good faith belief that the disputed use is not authorized</li>
  <li class="mb-2">A statement, under penalty of perjury, that the information in your notice is accurate</li>
</ul>

<p class="mb-8 text-lg font-bold">7. USER CONDUCT</p>

<p class="mb-4">You agree that you will not engage in any activity that interferes with or disrupts the QuantifyAI Sites and Features or our Services. You further agree that your use of the QuantifyAI Sites and Features and our Services shall not be fraudulent, deceptive, or unlawful.</p>

<p class="mb-8 text-lg font-bold">8. COMMUNICATIONS CHANNELS</p>

<p class="mb-4">The QuantifyAI Sites and Features may include communication channels such as forums, communities, or chat areas. The Company has no obligation to monitor these Communication Channels but reserves the right to review and remove materials at any time.</p>

<p class="mb-8 text-lg font-bold">9. DISCLAIMER OF WARRANTIES AND LIMITATIONS OF LIABILITY</p>

<p class="mb-4">To the maximum extent permitted by applicable law, the Company disclaims any and all guarantees, warranties and representations, express or implied, in connection with the QuantifyAI Sites and Features, or our Services.</p>

<p class="mb-8 text-lg font-bold">10. COMPLIANCE WITH FTC GUIDES</p>

<p class="mb-4">If you choose to promote our services to the public, you agree to comply with the FTC Guides Concerning the Use of Endorsements and Testimonials in Advertising.</p>

<p class="mb-8 text-lg font-bold">11. BINDING ARBITRATION</p>

<p class="mb-4">All disputes and claims between parties shall be resolved through binding arbitration rather than in court, except that you may assert claims in small claims court if your claims qualify.</p>

<p class="mb-8 text-lg font-bold">12. TAX MATTERS</p>

<p class="mb-4">You are responsible for any and all tax liability arising from or associated with your use of the QuantifyAI Sites and Features or our Services, including liability arising from your accrual or redemption of Rewards.</p>

<p class="mb-8 text-lg font-bold">13. EXCLUDED USERS AND TERRITORIES</p>

<p class="mb-4">You are not permitted to use the QuantifyAI Sites and Features or our Services if you are:</p>

<ul class="list-disc pl-8 mb-4">
  <li class="mb-2">Located in, under the control of, or a national or resident of any embargoed country</li>
  <li class="mb-2">Identified as a "Specially Designated National"</li>
  <li class="mb-2">Placed on any U.S. export control list</li>
</ul>

<p class="mb-8 text-lg font-bold">14. INTERNATIONAL USERS</p>

<p class="mb-4">The QuantifyAI Sites and Features and our Services are controlled, operated, and administered from our offices within the United States of America. Access from territories where the contents are illegal is prohibited.</p>

<p class="mb-8 text-lg font-bold">15. NOTIFICATION OF CHANGES</p>

<p class="mb-4">We reserve the right to make changes to these Terms from time to time. We will provide notice of such changes by sending you an administrative email and/or posting those changes on the QuantifyAI Sites and Features.</p>

<p class="mb-8 text-lg font-bold">16. RESIDENTS OF QUEBEC, CANADA</p>

<p class="mb-4">Special provisions apply to residents of Quebec, Canada under the Quebec Consumer Protection Act (QCPA).</p>

<p class="mb-8 text-lg font-bold">17. MISCELLANEOUS</p>

<p class="mb-4">The Company's failure to exercise or enforce any right or provision of these Terms will not be deemed to be a waiver of such right or provision. If any provision is found invalid, the parties agree that such provision should be modified to be valid and enforceable.</p>

<p class="mb-8 text-lg font-bold">18. CONTACT US</p>

<p class="mb-4">If you have any questions or concerns regarding these Terms or your use of any QuantifyAI Sites and Features or our Services, please contact us through our Help Center or contact options located in the applicable QuantifyAI Sites and Features footer.</p>

<p class="mb-4">To contact us by mail, send your correspondence to:</p>

<p class="mb-4">
QuantifyAI, LLC<br>
3200 Paseo Village Way #2337<br>
San Diego, CA 92130<br>
USA
</p>`,
    },
  };

  // Open modal function
  function openModal(type) {
    modalTitle.textContent = modalContents[type].title;
    modalContent.innerHTML = modalContents[type].content;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
  }

  // Close modal function
  function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = ''; // Restore scrolling
  }

  // Event listeners for opening modal
  document.querySelectorAll('a[href=""]').forEach((link) => {
    if (link.textContent.toLowerCase().includes('privacy')) {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        openModal('privacy');
      });
    } else if (link.textContent.toLowerCase().includes('terms')) {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        openModal('terms');
      });
    }
  });

  // Close modal when clicking close button
  closeButton.addEventListener('click', closeModal);

  // Close modal when clicking outside
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      closeModal();
    }
  });

  // Close modal with escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
      closeModal();
    }
  });
}
