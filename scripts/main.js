console.log('main.js loaded – PRIVACY_POLICY_2025_12_16');

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
      title: 'OpinionElite Privacy Policy',
      content: `
<p class="mb-4">Last Updated: 12/16/2025</p>

<p class="mb-4">
  This Privacy Policy applies to individuals residing in the US, Canada, Europe, and other locations who access or use OpinionElite’s websites, applications, online survey systems, browser extensions, emails, and any other digital properties or services we make available (collectively, the “OpinionElite Site and Features”). OpinionElite (“OpinionElite,” “we,” “our,” or “us”) is a United States–based technology and market research company. This Privacy Policy explains how we collect, use, store, share, secure, and otherwise process personal information through the OpinionElite Site and Features.
</p>

<p class="mb-4">
  Your use of the OpinionElite Site and Features constitutes your acceptance of this Privacy Policy and our Terms of Use, which include a binding arbitration clause and a waiver of class action rights. If you do not agree to the terms described in this Privacy Policy, you should immediately discontinue your use of the OpinionElite Site and Features.
</p>

<p class="mb-4">
  Unless otherwise stated, this Privacy Policy applies only to information collected online through the OpinionElite Site and Features, and not to information collected offline or through unrelated websites, applications, or services to which we may provide links.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">1. Overview and Scope of This Policy</h3>
<p class="mb-4">
  This Privacy Policy applies to individuals residing in the US, Canada, Europe, and other locations who access or use the OpinionElite Site and Features. It explains how we collect, use, store, share, secure, and otherwise process personal information through the OpinionElite Site and Features.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">2. Information You Provide and What We Automatically Collect</h3>
<p class="mb-4">
  When you visit or use the OpinionElite Site and Features, you may provide, and we may automatically collect, information that identifies you personally. “Personal Information” means information that can reasonably be used to identify, contact, or locate you.
</p>
<p class="mb-2">Examples of Personal Information include, without limitation:</p>
<ul class="list-disc pl-8 mb-4">
  <li>Your name</li>
  <li>Your email address</li>
  <li>Your phone number</li>
  <li>Your date of birth</li>
  <li>Your mailing address and ZIP or postal code</li>
  <li>Device identifiers and social media profile information</li>
</ul>
<p class="mb-4">
  We may also collect demographic information, survey response data, and account credentials when you register or participate in research activities. Personal Information may be collected when you create an account, participate in surveys, communicate with us, join email lists, submit feedback, install browser extensions, or otherwise engage with the OpinionElite Site and Features.
</p>
<p class="mb-4">
  We may also collect “Non-Personal Information” (“Non-PI”), which cannot reasonably identify you on its own. If we combine Non-PI with Personal Information, we treat the combined data as Personal Information. If you choose not to provide certain Personal Information, you may be unable to access some or all of the OpinionElite Site and Features.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">3. Data Supplemented From External Sources</h3>
<p class="mb-4">
  We may receive additional information about you from trusted third parties, including research partners, identity and fraud-prevention vendors, analytics providers, and social media networks when you choose to link or interact with them through the OpinionElite Site and Features. Such information may include confirmation of your identity, eligibility checks, account verification details, and information necessary to validate survey participation. Any information received from third parties and combined with information collected through the OpinionElite Site and Features will be treated under this Privacy Policy.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">4. How OpinionElite Utilizes and Shares Information</h3>
<p class="mb-4">
  OpinionElite uses Personal Information and Non-PI to operate, maintain, and improve the OpinionElite Site and Features; provide surveys, research opportunities, content, incentives, and relevant communications; validate data quality; prevent fraudulent activity; and support analytics, optimization, compliance, and administrative functions.
</p>
<p class="mb-4">
  Personal Information may also be used for research, internal analysis, troubleshooting, service improvements, and other business-related purposes as permitted by applicable law. We may share information with our affiliated entities, service providers, survey partners, clients, incentive providers, analytics companies, and other trusted third parties who assist in delivering the OpinionElite Site and Features.
</p>
<p class="mb-4">
  These parties may help authenticate users, facilitate rewards, host data, enhance security, or conduct research analytics. We may also share aggregated or anonymized information that does not identify you personally for business or research purposes.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">5. Public Interactions, Contributions, and Visible Content</h3>
<p class="mb-4">
  The OpinionElite Site and Features may include blogs, forums, feedback tools, or public-facing features that allow you to submit comments, text, photos, videos, audio, or other “User Content.” Any User Content you submit may be publicly visible depending on the feature and may include metadata that could contain information about you.
</p>
<p class="mb-4">
  If you choose to voluntarily disclose Personal Information through User Content, that information becomes publicly available and is not protected by this Privacy Policy. By submitting User Content, you grant OpinionElite permission to use, reproduce, adapt, display, or distribute such content for operational, administrative, or promotional purposes.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">6. Links and Connections to Other Digital Services</h3>
<p class="mb-4">
  The OpinionElite Site and Features may contain links to third-party websites, applications, or tools that we do not own or control. These third-party services may collect Personal Information or other data from you independently, including through their own tracking technologies. Their privacy and data-handling practices are governed by their own policies, not by this Privacy Policy. We encourage you to review those third-party privacy statements before interacting with their services.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">7. Advertising, Measurement Partners, and Analytical Technologies</h3>
<p class="mb-4">
  OpinionElite may work with advertising networks, attribution platforms, or analytics providers who may place cookies or tracking technologies on your device to measure engagement, provide aggregated analytics, and improve campaign performance. These third parties may collect information about your interactions with the OpinionElite Site and Features and other digital properties over time. Their data collection activities are governed by their own privacy policies.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">8. Use of Cookies, Device Signals, and Tracking Methods</h3>
<p class="mb-4">
  OpinionElite and certain trusted third parties use cookies, pixel tags, web beacons, device fingerprinting technologies, embedded scripts, session replay tools, and similar tracking mechanisms to analyze usage patterns, authenticate users, maintain sessions, detect fraud, deliver surveys, improve system performance, and personalize your experience.
</p>
<p class="mb-4">
  You may disable or delete cookies through your browser settings; however, doing so may limit functionality and affect your ability to fully use the OpinionElite Site and Features.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">9. Situations in Which We May Disclose Information</h3>
<p class="mb-4">
  OpinionElite may disclose information when required to comply with legal or regulatory obligations, such as responding to subpoenas, court orders, lawful requests by government authorities, or other legal processes.
</p>
<p class="mb-4">
  We may also disclose information if we believe such action is necessary to investigate fraud, prevent harm, address security concerns, enforce our Terms of Use, or protect the rights, property, or safety of OpinionElite, our users, or the general public.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">10. Data Transfers During Company Restructuring or Sale</h3>
<p class="mb-4">
  If OpinionElite undergoes a business transition such as a merger, acquisition, divestiture, financing, sale of assets, or reorganization, your Personal Information and other data may be transferred to the acquiring or successor entity as part of the transaction. The new entity will continue to handle your Personal Information consistent with this Privacy Policy unless otherwise permitted by law.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">11. Managing Your Information, Preferences, and Communication Choices</h3>
<p class="mb-4">
  You are responsible for ensuring that the Personal Information you provide is accurate and current. If you have questions about updating your information, managing your privacy choices, or opting out of certain data uses, you may contact us using the information provided in the Contact Us section below.
</p>
<p class="mb-4">
  You may opt out of marketing communications by following instructions in those messages or by contacting us directly. Even after opting out of marketing communications, you may continue to receive transactional or administrative messages regarding your account or the OpinionElite Site and Features.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">12. How We Communicate Changes to This Policy</h3>
<p class="mb-4">
  OpinionElite may modify this Privacy Policy from time to time. If we make material changes, we may notify you through email or by posting an updated version in prominent areas of the OpinionElite Site and Features. Your continued use of the platform after such changes constitutes acceptance of the updated Privacy Policy. If you do not agree to updates, you should discontinue using the OpinionElite Site and Features.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">13. Policies Regarding Minors and Underage Users</h3>
<p class="mb-4">
  OpinionElite does not knowingly collect Personal Information from children under 13 years of age. For residents of the European Union and applicable European jurisdictions, the minimum participation age is 16, as required by GDPR, and the OpinionElite Site and Features are not intended for use by children under 13.
</p>
<p class="mb-4">
  If we become aware that we have collected Personal Information from a child under 13, we will take steps to delete such information promptly. If you believe that a child has provided Personal Information to OpinionElite, please contact us at <a href="mailto:Support@opinionelite.com" class="underline text-[#fd9f15]">Support@opinionelite.com</a>.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">14. Safeguards and Measures to Protect Information</h3>
<p class="mb-4">
  OpinionElite employs reasonable administrative, technical, and physical safeguards designed to protect Personal Information from unauthorized access, alteration, disclosure, or destruction. Despite our efforts, no method of internet transmission or electronic storage is completely secure, and we cannot guarantee the absolute security of your information. You acknowledge that transmission of Personal Information is done at your own risk.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">15. Where Data Is Processed and Governing Legal Framework</h3>
<p class="mb-4">
  OpinionElite processes and stores data primarily in the United States. Some processing may occur in other countries, including India and Europe, through service providers acting on our behalf.
</p>
<p class="mb-4">
  By using the OpinionElite Site and Features, you consent to the transfer and processing of your Personal Information in the United States and other locations where we or our service providers operate. The laws of the United States, including the State of Delaware, govern the interpretation and application of this Privacy Policy, regardless of conflict-of-law principles.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">16. Data Retention</h3>
<p class="mb-4">
  OpinionElite retains Personal Information only for as long as reasonably necessary to fulfill the purposes outlined in this Privacy Policy, including providing services, complying with legal obligations, resolving disputes, enforcing agreements, and maintaining business records.
</p>
<p class="mb-4">
  Retention periods may vary depending on the nature of the data, contractual requirements, and applicable laws. When Personal Information is no longer required, it is securely deleted, anonymized, or de-identified in accordance with our internal data retention and destruction policies.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">17. Additional Rights for Residents of California, Canada, and Other U.S. States (CCPA/CPRA and CPPA)</h3>
<p class="mb-4">
  If you are a resident of California, Canada, or another U.S. state with applicable consumer privacy laws, you may have additional rights, including:
</p>
<ul class="list-disc pl-8 mb-4">
  <li>Right to know / access the categories and specific pieces of Personal Information we have collected about you;</li>
  <li>Right to request deletion of Personal Information, subject to legal exceptions;</li>
  <li>Right to request correction of inaccurate Personal Information;</li>
  <li>Right to opt out of the sale or sharing of Personal Information, where applicable;</li>
  <li>Right to limit the use of Sensitive Personal Information, where applicable; and</li>
  <li>Right to non-discrimination for exercising your privacy rights.</li>
</ul>
<p class="mb-4">
  OpinionElite provides a mechanism for eligible users to opt out of the sale or sharing of Personal Information by submitting a request via email to <a href="mailto:support@opinionelite.com" class="underline text-[#fd9f15]">support@opinionelite.com</a> with the subject line “Do Not Sell or Share My Personal Information.”
</p>
<p class="mb-4">
  Requests to exercise rights under CCPA/CPRA or CPPA may be submitted by contacting us at <a href="mailto:support@opinionelite.com" class="underline text-[#fd9f15]">support@opinionelite.com</a>. We may require verification of identity before fulfilling requests.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">18. Additional Rights for Residents in the European Union and Europe</h3>
<p class="mb-4">
  If you reside in the European Union, the European Economic Area, or other European regions with applicable data protection laws, you have additional rights under the General Data Protection Regulation (“GDPR”) and comparable European laws. These rights include the ability to request access to your Personal Information, correct inaccuracies, request deletion, restrict processing, object to processing based on legitimate interests, request data portability, and withdraw consent at any time when processing is based on consent.
</p>
<p class="mb-4">
  You may also lodge complaints with your local data protection authority. OpinionElite processes European Personal Information based on lawful grounds such as your consent, our legitimate interests (for example, fraud prevention, analytics, and service improvement), the performance of contracts (such as reward fulfillment), and compliance with legal obligations.
</p>
<p class="mb-4">
  When European data is transferred outside Europe, including to the United States, we use appropriate safeguards such as Standard Contractual Clauses, Data Processing Agreements, and technical measures designed to protect the data during transfer and processing. Requests to exercise European privacy rights may be submitted to <a href="mailto:Support@opinionelite.com" class="underline text-[#fd9f15]">Support@opinionelite.com</a>.
</p>

<h3 class="mt-6 mb-2 font-semibold text-lg">19. How to Contact OpinionElite Regarding Privacy</h3>
<p class="mb-4">
  If you have questions about this Privacy Policy or wish to exercise your privacy rights, please contact us at:
</p>
<p class="mb-2">
  Email: <a href="mailto:Support@opinionelite.com" class="underline text-[#fd9f15]">Support@opinionelite.com</a>
</p>
<p class="mb-4">
  DOP: Rajesh Dechineni<br/>
  OpinionElite – Privacy Office
</p>
      `,
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
