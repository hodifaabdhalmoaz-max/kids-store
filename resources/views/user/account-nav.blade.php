<ul class="account-nav">
     <li><a href="{{route('user.index')}}" class="menu-link menu-link_us-s">لوحة التحكم</a></li>
     <li><a href="account-orders.html" class="menu-link menu-link_us-s">الطلبات</a></li>
     <li><a href="account-address.html" class="menu-link menu-link_us-s">العناوين</a></li>
     <li><a href="account-details.html" class="menu-link menu-link_us-s">تفاصيل الحساب</a></li>
     <li><a href="account-wishlist.html" class="menu-link menu-link_us-s">المفضلة</a></li>

     <li>
        <form method="POST" action="{{route('logout')}}" id="logout-form">
            @csrf
            <a href="{{route('logout')}}" class="menu-link menu-link_us-s" onclick="event.preventDefault();document.getElementById('logout-form').submit();">تسجيل الخروج</a>
        </form>
     </li>
 </ul>
