/**
 * App Navigator
 * Main navigation structure
 */

import React from 'react';
import {createStackNavigator} from '@react-navigation/stack';
import {createBottomTabNavigator} from '@react-navigation/bottom-tabs';
import Icon from 'react-native-vector-icons/Ionicons';
import {useAuth} from '../context/AuthContext';
import {colors} from '../utils/theme';

// Auth Screens
import LoginScreen from '../screens/Auth/LoginScreen';
import RegisterScreen from '../screens/Auth/RegisterScreen';

// Main Screens
import DashboardScreen from '../screens/Dashboard/DashboardScreen';
import CustomersListScreen from '../screens/Customers/CustomersListScreen';
import CustomerDetailScreen from '../screens/Customers/CustomerDetailScreen';
import AIGeneratorScreen from '../screens/AI/AIGeneratorScreen';
import CampaignsScreen from '../screens/Campaigns/CampaignsScreen';
import ProfileScreen from '../screens/Profile/ProfileScreen';

// Loading Screen
import LoadingScreen from '../screens/LoadingScreen';

const Stack = createStackNavigator();
const Tab = createBottomTabNavigator();

// Tab Navigator for authenticated users
const TabNavigator = () => {
  return (
    <Tab.Navigator
      screenOptions={({route}) => ({
        headerShown: false,
        tabBarActiveTintColor: colors.primary,
        tabBarInactiveTintColor: colors.gray,
        tabBarStyle: {
          backgroundColor: colors.white,
          borderTopWidth: 1,
          borderTopColor: colors.borderLight,
          height: 60,
          paddingBottom: 8,
          paddingTop: 8,
        },
        tabBarLabelStyle: {
          fontSize: 12,
          fontWeight: '600',
        },
        tabBarIcon: ({focused, color, size}) => {
          let iconName;

          switch (route.name) {
            case 'DashboardTab':
              iconName = focused ? 'speedometer' : 'speedometer-outline';
              break;
            case 'CustomersTab':
              iconName = focused ? 'people' : 'people-outline';
              break;
            case 'AITab':
              iconName = focused ? 'sparkles' : 'sparkles-outline';
              break;
            case 'CampaignsTab':
              iconName = focused ? 'mail' : 'mail-outline';
              break;
            case 'ProfileTab':
              iconName = focused ? 'person-circle' : 'person-circle-outline';
              break;
          }

          return <Icon name={iconName} size={size} color={color} />;
        },
      })}>
      <Tab.Screen
        name="DashboardTab"
        component={DashboardScreen}
        options={{tabBarLabel: 'Dashboard'}}
      />
      <Tab.Screen
        name="CustomersTab"
        component={CustomersStackNavigator}
        options={{tabBarLabel: 'Clients'}}
      />
      <Tab.Screen
        name="AITab"
        component={AIGeneratorScreen}
        options={{tabBarLabel: 'IA'}}
      />
      <Tab.Screen
        name="CampaignsTab"
        component={CampaignsScreen}
        options={{tabBarLabel: 'Campagnes'}}
      />
      <Tab.Screen
        name="ProfileTab"
        component={ProfileScreen}
        options={{tabBarLabel: 'Profil'}}
      />
    </Tab.Navigator>
  );
};

// Stack Navigator for Customers
const CustomersStackNavigator = () => {
  return (
    <Stack.Navigator
      screenOptions={{
        headerShown: false,
      }}>
      <Stack.Screen name="CustomersList" component={CustomersListScreen} />
      <Stack.Screen name="CustomerDetail" component={CustomerDetailScreen} />
    </Stack.Navigator>
  );
};

// Auth Navigator for non-authenticated users
const AuthNavigator = () => {
  return (
    <Stack.Navigator
      screenOptions={{
        headerShown: false,
      }}>
      <Stack.Screen name="Login" component={LoginScreen} />
      <Stack.Screen name="Register" component={RegisterScreen} />
    </Stack.Navigator>
  );
};

// Main App Navigator
const AppNavigator = () => {
  const {isAuthenticated, loading} = useAuth();

  if (loading) {
    return <LoadingScreen />;
  }

  return (
    <Stack.Navigator screenOptions={{headerShown: false}}>
      {isAuthenticated ? (
        <Stack.Screen name="Main" component={TabNavigator} />
      ) : (
        <Stack.Screen name="Auth" component={AuthNavigator} />
      )}
    </Stack.Navigator>
  );
};

export default AppNavigator;
