<x-app-layout>
    <div class="container mx-auto p-4">
        <form id="contact-form" 
              method="POST" 
              action="https://formsubmit.co/ramiabbassi53@gmail.com" 
              class="space-y-6">
            
            <!-- Name and Email -->
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block font-medium text-gray-700">Your Name</label>
                    <input type="text" name="name" id="name" required placeholder="Enter your name"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="email" class="block font-medium text-gray-700">Your Email</label>
                    <input type="email" name="email" id="email" required placeholder="Enter your email"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <!-- Subject -->
            <div>
                <label for="subject" class="block font-medium text-gray-700">Subject</label>
                <input type="text" name="subject" id="subject" required placeholder="Subject of your message"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Message -->
            <div>
                <label for="message" class="block font-medium text-gray-700">Message</label>
                <textarea name="message" id="message" rows="6" required placeholder="type your message here"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Write your message here..."></textarea>
            </div>

            <!-- Anti-spam hidden field (FormSubmit requirement) -->
            <input type="hidden" name="_captcha" value="false">

            <!-- Submit Button -->
            <div class="flex justify-center">
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-gray-600 font-medium rounded-md border border-gray-300 hover:bg-indigo-700 hover:text-gray-900 transition">
                    Send Message
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
